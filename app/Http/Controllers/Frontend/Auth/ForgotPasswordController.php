<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ForgotPasswordRequest;
use App\Library\Models\Token;
use App\Library\Models\User;
use App\Library\Services\Notifications\MailResetPassword;

class ForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgotPass()
    {
        return view('frontend.auth.forgot-password');
    }

    public function processForgotPass(ForgotPasswordRequest $request)
    {
        //get user info from email
        $userInfo = User::instance()->getDetailUserByEmail($request->email);

        //create token link to active account
        $key = Token::instance()->insertTokenKey([
            'type' => config('constants.token.type.reset_password'),
            'user_id' => $userInfo->id,
        ]);

        //send mail verify account
        $userInfo->notify(new MailResetPassword([
            'url' => route('auth.reset-password', [$key])
        ]));

        return redirect(route('auth.message'))->withInput(['message' => [ 'Một email hướng dẫn thay đổi mật khẩu đã được gửi đến thư của bạn, vui lòng kiểm tra và làm theo hướng dẫn trong thư.']]);
    }
}
