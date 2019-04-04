<?php

namespace App\Library\Services\Auth;

interface AuthServiceContract
{
    public function getUserDetail();
    public function checkAttempt($credentials);
    public function getUserId();
}
