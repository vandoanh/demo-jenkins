<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;
use Illuminate\Support\Str;

class Tag
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'tag_detail_%s';
    private $key_list = 'tag_list';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailTag($id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [Str::slug($id)]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailTag($id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [Str::slug($id)]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListTag()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListTag($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
}
