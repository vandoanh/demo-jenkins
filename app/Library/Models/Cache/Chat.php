<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;

class Chat
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_list_message = 'chat_list_message';
    private $key_list_user = 'chat_list_user';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getListMessage()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_message);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListMessage($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_message);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListUser()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_user);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = [];
        }

        return $arrData;
    }

    public function setListUser($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_user);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
}
