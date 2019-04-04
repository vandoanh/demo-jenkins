<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\ApiLog as MySqlApiLog;

class ApiLog
{
    use Singleton;

    private $mysql;

    public function __construct()
    {
        $this->mysql = MySqlApiLog::instance();
    }

    public function createLog($params)
    {
        return $this->mysql->createLog($params);
    }
}
