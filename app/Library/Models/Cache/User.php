<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;

class User
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'user_detail_%s';
    private $key_detail_email = 'user_detail_email_%s';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailUser($user_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$user_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailUser($user_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$user_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getDetailUserByEmail($email)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail_email, [$email]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailUserByEmail($email, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail_email, [$email]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
}
