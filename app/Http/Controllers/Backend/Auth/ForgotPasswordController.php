<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\ForgotPasswordRequest;
use Illuminate\Support\Facades\DB;
use App\Library\Models\User;
use App\Library\Models\Token;
use App\Library\Services\Notifications\MailResetPassword;
use App\Library\Services\CommonService;

class ForgotPasswordController extends BackendController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:' . $this->guard);
    }

    public function forgotPass()
    {
        return view('backend.auth.forgotpass');
    }

    public function processForgotPass(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();

        try {
            if ($userInfo = User::instance()->getUserDetailByEmail($request->input('email'))) {
                $key = Token::instance()->insertTokenKey([
                    'type' => config('constants.token.type.reset_password'),
                    'user_id' => $userInfo->id,
                ]);

                DB::commit();

                //send mail
                $userInfo->notify(new MailResetPassword([
                    'url' => route('backend.auth.reset-password', $key)
                ]));

                return redirect()->route('backend.auth.forgot-password-complete');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            abort(500);
        }
    }

    public function forgotPassComplete()
    {
        return view('backend.auth.forgotpass_complete');
    }
}
