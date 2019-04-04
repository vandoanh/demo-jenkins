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
use App\Library\Services\Jobs\WriteActiveLog;

class UserController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/v1/user/logout",
     *      summary="Logout",
     *      operationId="logout",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="token",
     *          in="header",
     *          description="API token",
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
     *          response=500,
     *          description="Internal server",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(ref="#/components/schemas/Response")}
     *          )
     *      ),
     *  )
     */
    public function logout(Request $request)
    {
        try {
            $userInfo = $this->authService->getUserDetail();

            $request->user()->token()->revoke();

            dispatch(new WriteActiveLog([
                'user_id' => $userInfo->id,
                'module' => config('constants.log.module.api'),
                'type' => config('constants.log.type.logout'),
                'ip_address' => $request->getClientIp(),
                'user_agent' => $request->header('User-Agent'),
                'cookie_val' => auth()->getSession()->getId(),
            ]))->onQueue('log');

            return CommonService::setSuccessApi();
        } catch (\Exception $ex) {
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/v1/user/info",
     *      summary="Get user info",
     *      operationId="getUserInfo",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="token",
     *          in="header",
     *          description="API token",
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
     *              @OA\Property(property="data", ref="#/components/schemas/User")
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
    public function getUserInfo(Request $request)
    {
        try {
            $userInfo = $this->authService->getUserDetail();
            $userResource = new UserResource($userInfo);

            return CommonService::setSuccessApi($userResource->resolve($request));
        } catch (\Exception $ex) {
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/v1/user/update-info",
     *      summary="Update user info",
     *      operationId="updateUserInfo",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="token",
     *          in="header",
     *          description="Token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="fullname",
     *          in="query",
     *          description="Full name",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="Email",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="birthday",
     *          in="query",
     *          description="Birth Day",
     *          required=false,
     *          @OA\Schema(
     *              type="date",
     *              format="Y-m-d"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="Description",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation post failed",
     *          @OA\JsonContent(
     *                  allOf={@OA\Property(property="response",ref="#/components/schemas/Response")}
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              allOf={@OA\Property(property="response",ref="#/components/schemas/Response")},
     *              @OA\Property(property="data", ref="#/components/schemas/User")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server",
     *          @OA\JsonContent(
     *             allOf={@OA\Property(property="response",ref="#/components/schemas/Response")}
     *          )
     *      ),
     *  )
     */

    public function updateUserInfo(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'nullable|max:200',
                'birthday' => 'nullable|date_format:"Y-m-d"|before:' . Carbon::now()->format('Y-m-d'),
                'gender' => 'nullable|in:' . config('constants.user.gender'),
                'avatar' => 'nullable|file|image|mimes:jpeg,png,jpg'
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            $userInfo = $this->authService->getUserDetail();

            if ($request->avatar) {
                $file = $request->avatar;
                $file_name = str_slug($userInfo->fullname) . '_' . CommonService::randomInt() . '.' . $file->extension();

                $avatar = CommonService::saveImageFromFile($file, $file_name, config('constants.image.avatar.folder'), false);
            } else {
                $avatar = null;
            }

            //update user
            $userInfo = User::instance()->updateUser($userInfo->id, [
                'fullname' => $request->fullname,
                'gender' => $request->gender ?? null,
                'avatar' => $avatar,
                'birthday' => $request->birthday ? Carbon::parse($request->birthday) : null,
            ]);

            $userResource = new UserResource($userInfo);

            DB::commit();

            return CommonService::setSuccessApi($userResource->resolve($request));
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|password',
                'password_confirmation' => 'required|same:password'
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            $userInfo = $this->authService->getUserDetail();

            //update user
            $userInfo = User::instance()->updateUser($userInfo->id, [
                'password' => bcrypt($request->password)
            ]);

            $userResource = new UserResource($userInfo);

            DB::commit();

            return CommonService::setSuccessApi($userResource->resolve($request));
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }

    public function uploadAvatar(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|file|image|mimes:jpeg,png,jpg',
            ]);

            if ($validator->fails()) {
                DB::rollBack();

                return CommonService::setErrorApi($validator->errors());
            }

            $file = $request->avatar;

            $userInfo = $this->authService->getUserDetail();
            $file_name = str_slug($userInfo->fullname) . '_' . CommonService::randomInt() . '.' . $file->extension();

            $avatar = CommonService::saveImageFromFile($file, $file_name, config('constants.image.avatar.folder'), false);

            //update user
            $userInfo = User::instance()->updateUser($userInfo->id, [
                'avatar' => $avatar
            ]);

            $userResource = new UserResource($userInfo);

            DB::commit();

            return CommonService::setSuccessApi($userResource->resolve($request));
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->logError($request, $ex);

            return CommonService::setErrorApi();
        }
    }
}
