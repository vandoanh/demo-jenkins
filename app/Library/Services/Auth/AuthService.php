<?php

namespace App\Library\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceContract
{
    public function getUserDetail()
    {
        return Auth::user();
    }

    public function checkAttempt($credentials)
    {
        if (Auth::attempt($credentials)) {
            return true;
        }

        return false;
    }

    public function getUserId()
    {
        return Auth::user()->id;
    }
}
