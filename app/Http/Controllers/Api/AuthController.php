<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Resources\UserResource;
use App\Library\Services\CommonService;
use App\Library\Models\User;
use Carbon\Carbon;
use App\Library\Models\Token;
use App\Library\Services\Notifications\MailActiveAccount;
use App\Library\Services\Notifications\MailResetPassword;
use App\Library\Services\Jobs\WriteActiveLog;

class AuthController extends ApiController
{
    /**
     *  @OA\Post(
     *      path="/api/v1/auth/login",
     *      summary="Login",
     *      operationId="login",
     *      tags={"Auth"},
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User's email",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="User's password",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")},
     *              @OA\Property(property="data", type="object", allOf={@OA\Property(ref="#/components/schemas/Token"), @OA\Property(ref="#/components/schemas/User")})
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid input",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Login failed",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *  )
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|password'
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'status' => config('constants.user.status.active')
            ];

            if ($this->authService->checkAttempt($credentials)) {
                $userInfo = $this->authService->getUserDetail();
                $userResource = new UserResource($userInfo);
                $data = $userResource->resolve($request);

                if ($this->model->count() > 0) {
                    $response = $this->guzzleHttpClient->post(url('/oauth/token', [], $request->isSecure()), [
                        'form_params' => [
                            'grant_type' => 'password',
                            'client_id' => $this->model->id,
                            'client_secret' => $this->model->secret,
                            'username' => $credentials['email'],
                            'password' => $credentials['password'],
                            'scope' => '',
                        ],
                    ]);

                    $dataOauth = json_decode((string) $response->getBody(), true);
                    $data = array_merge($dataOauth, $data);
                }

                // write log
                dispatch(new WriteActiveLog([
                    'user_id' => $userInfo->id,
                    'module' => config('constants.log.module.api'),
                    'type' => config('constants.log.type.login'),
                    'ip_address' => $request->getClientIp(),
                    'user_agent' => $request->header('User-Agent'),
                    'cookie_val' => auth()->getSession()->getId(),
                ]))->onQueue('log');

                return CommonService::setSuccessApi($data);
            }

            DB::rollBack();

            return CommonService::setErrorApi('Login failed!');
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|max:200',
                'email' => 'required|email|max:200|unique:users,email,null,id',
                'password' => 'required|password',
                'confirm_password' => 'same:password',
                'birthday' => 'nullable|date_format:Y-m-d',
                'gender' => 'nullable|in:' . config('constants.user.gender'),
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            //code insert user info to db
            $userInfo = User::instance()->createUser([
                'email' => $request->email,
                'fullname' => $request->fullname,
                'birthday' => $request->birthday ? Carbon::parse($request->birthday) : null,
                'gender' => $request->gender,
                'avatar' => config('constants.image.avatar.name'),
                'password' => bcrypt($request->password),
                'user_type' => config('constants.user.type.member'),
                'status' => config('constants.status.inactive')
            ]);

            //create token link to active account
            $key = Token::instance()->insertTokenKey([
                'type' => config('constants.token.type.active_account'),
                'user_id' => $userInfo->id,
            ]);

            //send mail verify account
            $userInfo->notify(new MailActiveAccount([
                'url' => route('auth.verify', [$key])
            ]));

            DB::commit();

            return CommonService::setSuccessApi();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/v1/auth/forgot-password",
     *      summary="Forgot password",
     *      operationId="forgotPassword",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User's email",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid input",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *  )
     */
    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            if ($userInfo = User::instance()->getUserDetailByEmail($request->input('email'))) {
                $key = Token::instance()->insertTokenKey([
                    'type' => config('constants.token.type.reset_password'),
                    'user_id' => $userInfo->id,
                ]);

                DB::commit();

                //send mail
                $userInfo->notify(new MailResetPassword([
                    'url' => route('auth.reset-password', [$key])
                ]));

                //code process forgot password
                return CommonService::setSuccessApi();
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/v1/auth/refresh-token",
     *      summary="Refresh token",
     *      operationId="refreshToken",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="refresh_token",
     *          in="query",
     *          description="API refresh token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")},
     *              @OA\Property(property="data", type="object", allOf={@OA\Property(ref="#/components/schemas/Token"), @OA\Property(ref="#/components/schemas/User")})
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid input",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *  )
     */
    public function refreshToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required',
            ]);

            if ($validator->fails()) {
                return CommonService::setErrorApi($validator->errors());
            }

            $response = $this->guzzleHttpClient->post(url('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => $this->model->id,
                    'client_secret' => $this->model->secret,
                    'scope' => '',
                ],
            ]);

            return CommonService::setSuccessApi(json_decode((string) $response->getBody(), true));
        } catch (\Exception $ex) {
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }
}
