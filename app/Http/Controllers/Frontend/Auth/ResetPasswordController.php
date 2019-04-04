<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ResetPasswordRequest;
use App\Library\Models\Token;
use App\Library\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo;

    public function __construct()
    {
        $this->middleware('guest');

        $this->redirectTo = route('auth.message');
    }

    public function showResetForm($token)
    {
        $tokenInfo = Token::instance()->getTokenKey([
            'type' => config('constants.token.type.reset_password'),
            'key' => $token,
        ]);

        if (!$tokenInfo) {
            abort(404);
        }

        return view('frontend.auth.reset-password')->with([
            'token' => $token,
        ]);
    }

    public function processResetPass(ResetPasswordRequest $request, $token)
    {
        $tokenInfo = Token::instance()->getTokenKey([
            'type' => config('constants.token.type.reset_password'),
            'key' => $token,
        ]);

        if (!$tokenInfo) {
            abort(404);
        }

        User::instance()->updateUser($tokenInfo->user_id, [
            'password' => bcrypt($request->password)
        ]);

        return redirect($this->redirectTo)->withInput(['message' => ['Reset password succeed!']]);
    }
}
