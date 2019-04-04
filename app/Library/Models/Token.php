<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\Token as MySqlToken;
use App\Library\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Token
{
    use Singleton;

    private $mysql;
    private $expiredTime = '24';

    public function __construct()
    {
        $this->mysql = MySqlToken::instance();
    }

    public function insertTokenKey($params)
    {
        //first delete all token match token type and user id
        $this->deleteTokenKey([
            'type' => $params['type'],
            'user_id' => $params['user_id'],
        ]);

        $key = CommonService::encrypt([
            'key' => (string)Str::uuid(),
            'user_id' => $params['user_id']
        ]);

        $this->mysql->insertTokenKey([
            'type' => $params['type'],
            'key' => $key,
            'user_id' => $params['user_id'],
            'expired_at' => Carbon::now()->addHours($this->expiredTime),
        ]);

        return $key;
    }

    public function getTokenKey($params)
    {
        return $this->mysql->getTokenKey($params);
    }

    public function deleteTokenKey($params)
    {
        return $this->mysql->deleteTokenKey($params);
    }
}
