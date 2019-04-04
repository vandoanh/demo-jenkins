<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\Chat as MySqlChat;
use App\Library\Models\Cache\Chat as CacheChat;
use Carbon\Carbon;

class Chat
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;

    public function __construct()
    {
        $this->mysql = MySqlChat::instance();
        $this->cache = CacheChat::instance();
        $this->enable_cache = false;//config('site.cache.enable');
    }

    public function getListMessage($pre_cache = false)
    {
        $arrData = $this->cache->getListMessage();

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListMessage([
                'date_from' => Carbon::now()->format('Y-m-d 0:0:0'),
                'date_to' => Carbon::now()->format('Y-m-d 23:59:59'),
            ]);

            if ($this->enable_cache) {
                $this->cache->setListMessage($arrData);
            }
        }

        return $arrData;
    }

    public function createMessage($params)
    {
        return $this->mysql->createMessage($params);
    }

    public function makeUserId($token)
    {
        $arrData = $this->cache->getListUser();

        if (empty($arrData) || !isset($arrData[$token])) {
            $arrData[$token] = count($arrData) + 1;

            $this->cache->setListUser($arrData);
        }

        return $arrData[$token];
    }
}
