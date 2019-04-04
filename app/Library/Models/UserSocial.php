<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\UserSocial as MySqlUserSocial;

class UserSocial
{
    use Singleton;

    private $mysql;

    public function __construct()
    {
        $this->mysql = MySqlUserSocial::instance();
    }

    public function createUser($params)
    {
        return $this->mysql->createUser($params);
    }
}
