<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\ResetPasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Library\Models\Token;
use App\Library\Models\MySql\User;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends BackendController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:' . $this->guard);

        $this->redirectTo = route('backend.dashboard');
    }

    public function resetPass($token)
    {
        $params = [
            'type' => config('constants.token.type.reset_password'),
            'key'  => $token
        ];
        $tokenInfo = Token::instance()->getTokenKey($params);
        if (!empty($tokenInfo)) {
            return view('backend.auth.resetpass', ['token' => $token]);
        } else {
            abort(404);
        }
    }

    public function processResetPass(ResetPasswordRequest $request)
    {
        // create new pass
        DB::beginTransaction();

        try {
            if (!empty($request['token'])) {
                $tokenInfoParams = [
                    'type' => config('constants.token.type.reset_password'),
                    'key'  => $request['token']
                ];
                $tokenInfo = Token::instance()->getTokenKey($tokenInfoParams);

                $userInfo = User::instance()->updateUser($tokenInfo['user_id'], [
                    'password' => bcrypt($request['password']),
                ]);

                //Destroy Token
                $params = [
                    'type'    => config('constants.token.type.reset_password'),
                    'user_id' => $request['user_id']
                ];
                Token::instance()->deleteTokenKey($params);

                DB::commit();

                return view('backend.auth.resetpass_complete');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            abort(500);
        }
    }
}
