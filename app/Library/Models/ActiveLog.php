<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\ActiveLog as MySqlActiveLog;

class ActiveLog
{
    use Singleton;

    private $mysql;

    public function __construct()
    {
        $this->mysql = MySqlActiveLog::instance();
    }

    public function createLog($params)
    {
        return $this->mysql->createLog($params);
    }
}
