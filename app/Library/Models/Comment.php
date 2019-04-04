<?php

namespace App\Library\Models;

use App\Library\Models\Cache\Comment as CacheComment;
use App\Library\Models\MySql\Comment as MySqlComment;
use App\Library\Models\Traits\Singleton;
use App\Library\Services\CommonService;

class Comment
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;
    private $max_record_cache = 500;

    public function __construct()
    {
        $this->mysql = MySqlComment::instance();
        $this->cache = CacheComment::instance();
        $this->enable_cache = config('site.cache.enable');
    }

    /**
     * Code for BE
     */
    public function createComment($params)
    {
        return $this->mysql->createComment($params);
    }

    public function updateComment($id, $params)
    {
        return $this->mysql->updateComment($id, $params);
    }

    public function updateCommentLike($id)
    {
        return $this->mysql->updateCommentLike($id);
    }

    public function deleteComment($id)
    {
        return $this->mysql->deleteComment($id);
    }

    public function changeStatus($id)
    {
        return $this->mysql->changeStatus($id);
    }

    public function getDetailCommentBE($id)
    {
        return $this->mysql->getDetailCommentBE($id);
    }

    public function getListCommentBE($params = [])
    {
        $params = array_merge([
            'content' => null,
            'item' => 0,
            'page' => 1,
        ], $params);

        return $this->mysql->getListCommentBE($params);
    }

    /**
     * Code for FE
     */
    public function getDetailComment($id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailComment($id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailComment($id);

            if ($this->enable_cache) {
                $this->cache->setDetailComment($id, $arrData);
            }
        }

        return $arrData;
    }

    public function getListCommentByPost($params, $pre_cache = false)
    {
        $params = array_merge(
            [
                'post_id' => 0,
                'item' => 0,
                'page' => 1,
            ],
            $params
        );

        //default return
        $arrData = collect([]);

        //count total record
        $records = $params['item'] + $params['page'] - 1;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getListCommentByPost($params['post_id']);
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListCommentByPost($params['post_id'], $this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListCommentByPost($params['post_id'], $arrData);
            }
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function countComment($arrPostId, $pre_cache = false)
    {
        $arrReturn = [];

        foreach (array_wrap($arrPostId) as $post_id) {
            $data = $this->cache->getCountComment($post_id);

            if ((!$this->enable_cache || $pre_cache) || empty($data)) {
                $data = $this->mysql->countComment($post_id);

                if ($this->enable_cache) {
                    $this->cache->setCountComment($post_id, $data);
                }
            }

            $arrReturn[$post_id] = $data;
        }

        if (!is_array($arrPostId)) {
            return $arrReturn[$arrPostId];
        }

        return $arrReturn;
    }

    /*private function removeChild($arrData)
    {
    if ($arrData->isEmpty()) {
    return $arrData;
    }

    foreach ($arrData as $key => $data) {
    if ($data->parent_id != 0) {
    $arrData->forget($key);
    }
    }

    return $arrData;
    }*/

    private function parseDetail($arrData)
    {
        if ($arrData->isNotEmpty()) {
            $arrData->transform(function ($data) {
                $postDetail = $this->getDetailComment($data->id);

                return $postDetail;
            });
        }

        return $arrData;
    }
}
