<?php

namespace App\Library\Models\Cache;

use App\Library\Models\Traits\Singleton;
use App\Library\Services\CachingService;
use App\Library\Services\CommonService;

class Comment
{
    use Singleton;

    private $cacheInstance;

    /**
     * Key cache
     */
    private $key_detail = 'comment_detail_%s';
    private $key_list_by_post = 'comment_list_by_post_%s';
    private $key_count_comment = 'comment_count_%s';

    public function __construct()
    {
        $this->cacheInstance = CachingService::getInstance();
    }

    public function getDetailComment($comment_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$comment_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        return $arrData;
    }

    public function setDetailComment($comment_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_detail, [$comment_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getListCommentByPost($post_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_post, [ $post_id]);
        $arrData = $this->cacheInstance->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = collect([]);
        }

        return $arrData;
    }

    public function setListCommentByPost($post_id, $arrData)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_list_by_post, [$post_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $arrData);
    }

    public function getCountComment($post_id)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this->key_count_comment, [$post_id]);
        $data = $this->cacheInstance->getCache($keyCache);

        return $data ?? 0;
    }

    public function setCountComment($post_id, $data)
    {
        //key cache
        $keyCache = CommonService::makeCacheKey($this-> key_count_comment, [$post_id]);
        $this->cacheInstance->writeCacheForever($keyCache, $data);
    }
}
