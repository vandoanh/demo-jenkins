<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;

class Notice
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'notice_detail_%s';
    private $key_list = 'notice_list';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailNotice($notice_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$notice_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailNotice($notice_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$notice_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListNotice()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListNotice($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
}
