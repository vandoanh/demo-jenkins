<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Library\Models\Token;
use App\Library\Models\User;
use Illuminate\Routing\Controller;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $tokenInfo = Token::instance()->getTokenKey([
            'type' => config('constants.token.type.active_account'),
            'key' => $token,
        ]);

        if (!$tokenInfo) {
            return redirect(route('auth.message'))->withInput(['error' => ['Your account is being activated or Token has been expired. Click here to request a new active link.']]);
        }

        //update status to active
        User::instance()->updateUser($tokenInfo->user_id, [
            'status' => config('constants.user.status.active')
        ]);

        //delete this token
        Token::instance()->deleteTokenKey([
            'type' => config('constants.token.type.active_account'),
            'user_id' => $tokenInfo->user_id,
        ]);

        return redirect(route('auth.message'))->withInput(['message' => ['Your account is being activated, please login to using it.']]);
    }

    public function resend()
    {
    }
}
