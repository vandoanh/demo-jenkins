<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;

class Category
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'cate_detail_%s';
    private $key_detail_by_code = 'cate_detail_by_code_%s';
    private $key_list_parent = 'cate_list_parent_%s';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailCategory($id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailCategory($id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
    
    public function getDetailCategoryByCode($code)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail_by_code, [$code]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }
    
    public function setDetailCategoryByCode($code, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail_by_code, [$code]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListParent($parent_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_parent, [$parent_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListParent($parent_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_parent, [$parent_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }
}
