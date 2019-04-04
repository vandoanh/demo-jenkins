<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;
use Illuminate\Support\Str;

class Post
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'post_detail_%s';
    private $key_list_set_on = 'post_by_cate_set_on_%s';
    private $key_list_on = 'post_by_cate_list_on_%s';
    private $key_build_top = 'post_build_top';
    private $key_list_all = 'post_all';
    private $key_list_by_user = 'post_by_user_%s_%s';
    private $key_list_by_tag = 'post_by_tag_%s';
    private $key_top_view = 'post_top_view';
    private $key_count_post = 'post_count_%s';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailPost($post_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$post_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailPost($post_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$post_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListPostByCateSetOn($cate_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_set_on, [$cate_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListPostByCateSetOn($cate_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_set_on, [$cate_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListPostByCateListOn($cate_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_on, [$cate_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListPostByCateListOn($cate_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_on, [$cate_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getBuildTop()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_build_top);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setBuildTop($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_build_top);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getAllPost()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_all);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setAllPost($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_all);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListPostByUser($user_id, $status)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_user, [$user_id, $status]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListPostByUser($user_id, $status, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_user, [$user_id, $status]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListPostByTag($tag_title)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_tag, [Str::slug($tag_title)]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListPostByTag($tag_title, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_tag, [Str::slug($tag_title)]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListTopView()
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_top_view);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListTopView($arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_top_view);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getCountPost($category_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_count_post, [$category_id]);
        $data = $this->cacheInstance->getCache($keyCache);

        return $data ?? 0;
    }

    public function setCountPost($category_id, $data)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_count_post, [$category_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $data);
    }
}
