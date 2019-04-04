<?php

namespace App\Library\Models;

use App\Library\Models\Cache\Post as CachePost;
use App\Library\Models\MySql\Post as MySqlPost;
use App\Library\Models\Traits\Singleton;
use App\Library\Services\CommonService;
use Carbon\Carbon;

class Post
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;
    private $max_record_cache = 500;
    private $arrExclude = [];

    public function __construct()
    {
        $this->mysql = MySqlPost::instance();
        $this->cache = CachePost::instance();
        $this->enable_cache = config('site.cache.enable');
    }

    public function setExclude($arrPostId)
    {
        $this->arrExclude = array_unique(array_merge($this->arrExclude, array_wrap($arrPostId)));
    }

    /**
     * Code for BE
     */
    public function createPost($params)
    {
        return $this->mysql->createPost($params);
    }

    public function createPostJob($attributes, $params)
    {
        if (empty($params['title'])) {
            return false;
        }

        $params['is_crawler'] = config('constants.post.crawler.yes');
        $params['user_id'] = 1;
        $params['priority'] = config('constants.post.priority.normal');

        //replace all tag a
        $params['content'] = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $params['content']);
        $params['content'] = preg_replace('#<iframe.*?>(.*?)</iframe>#i', '', $params['content']);

        //make score if status is published
        if ($params['status'] == config('constants.status.active')) {
            $params['content'] = CommonService::processImageContent($params['content']);
            $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $params['score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['priority'] . format_date($params['published_at'], 'His');
        }

        return $this->mysql->createPostJob($attributes, $params);
    }

    public function updatePost($params, $id)
    {
        return $this->mysql->updatePost($params, $id);
    }

    public function updatePostView($id)
    {
        return $this->mysql->updatePostView($id);
    }

    public function deletePost($id)
    {
        return $this->mysql->deletePost($id);
    }

    public function changeStatus($id)
    {
        return $this->mysql->changeStatus($id);
    }

    public function getDetailPostBE($id)
    {
        return $this->mysql->getDetailPostBE($id);
    }

    public function getListPostBE($params = [])
    {
        $params = array_merge([
            'category_id' => null,
            'status' => null,
            'title' => null,
            'user_id' => null,
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1,
        ], $params);

        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
        }
        if (!empty($params['date_to'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:59');
        }

        return $this->mysql->getListPostBE($params);
    }

    /**
     * Code for FE
     */
    public function getDetailPost($id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailPost($id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailPost($id);

            if ($arrData) {
                $arrData->share_url = route('post.detail', [$arrData->code, $arrData->id]);
            }

            if ($this->enable_cache) {
                $this->cache->setDetailPost($id, $arrData);
            }
        }

        return $arrData;
    }

    public function getListPostByCateSetOn($params, $pre_cache = false)
    {
        $params = array_merge([
            'category_id' => 0,
            'item' => 0,
            'page' => 1,
        ], $params);

        //default return
        $arrData = collect([]);

        //count total record
        $records = $params['item'] + $params['page'] - 1;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getListPostByCateSetOn($params['category_id']);
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListPostByCateSetOn($params['category_id'], $this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListPostByCateSetOn($params['category_id'], $arrData);
            }
        }

        if ($arrData->isNotEmpty() && !empty($this->arrExclude)) {
            $arrData = $arrData->whereNotIn('id', array_wrap($this->arrExclude));
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function getListPostByCateListOn($params, $pre_cache = false)
    {
        $params = array_merge(
            [
            'category_id' => 0,
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
            $arrData = $this->cache->getListPostByCateListOn($params['category_id']);
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListPostByCateListOn($params['category_id'], $this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListPostByCateListOn($params['category_id'], $arrData);
            }
        }

        if ($arrData->isNotEmpty() && !empty($this->arrExclude)) {
            $arrData = $arrData->whereNotIn('id', array_wrap($this->arrExclude));
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function getBuildTop($limit, $pre_cache = false)
    {
        //default return
        $arrData = collect([]);

        //count total record
        $records = $limit;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getBuildTop();
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getBuildTop($this->max_record_cache);

            if ($arrData->isNotEmpty()) {
                $arrDataByCate = $arrData->groupBy('category_id')->sortKeys();
                $arrDataTmp = collect();
                $totalItem = $arrDataByCate->sum(function ($items) {
                    return count($items);
                });

                do {
                    foreach ($arrDataByCate as $key => $items) {
                        $firstPost = $items->first();
                        $arrDataByCate[$key] = $items->forget(0);

                        $arrDataTmp->push($firstPost);
                        $totalItem--;
                    }
                } while ($totalItem >= 1);

                $arrData = $arrDataTmp;
            }

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setBuildTop($arrData);
            }
        }

        if ($arrData->isNotEmpty()) {
            $arrData = $arrData->slice(0, $limit);
        }

        return $this->parseDetail($arrData);
    }

    public function getAllPost($params, $pre_cache = false)
    {
        $params = array_merge(
            [
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
            $arrData = $this->cache->getAllPost();
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getAllPost($this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setAllPost($arrData);
            }
        }

        if ($arrData->isNotEmpty() && !empty($this->arrExclude)) {
            $arrData = $arrData->whereNotIn('id', array_wrap($this->arrExclude));
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function getListPostByUser($params, $pre_cache = false)
    {
        $params = array_merge([
            'user_id' => 0,
            'status' => null,
            'item' => 0,
            'page' => 1,
        ], $params);
        $status = empty($params['status']) ? 0 : $params['status'];

        //default return
        $arrData = collect([]);

        //count total record
        $records = $params['item'] + $params['page'] - 1;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getListPostByUser($params['user_id'], $status);
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListPostByUser($params['user_id'], $this->max_record_cache, $params['status']);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListPostByUser($params['user_id'], $status, $arrData);
            }
        }

        if ($arrData->isNotEmpty() && !empty($this->arrExclude)) {
            $arrData = $arrData->whereNotIn('id', array_wrap($this->arrExclude));
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function getListPostByTag($params, $pre_cache = false)
    {
        $params = array_merge(
            [
            'tag_title' => 0,
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
            $arrData = $this->cache->getListPostByTag($params['tag_title']);
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListPostByTag($params['tag_title'], $this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListPostByTag($params['tag_title'], $arrData);
            }
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    public function getListTopView($limit, $pre_cache = false)
    {
        //default return
        $arrData = collect([]);

        //count total record
        $records = $limit;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getListTopView();
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListTopView($this->max_record_cache);

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->getListTopView($arrData);
            }
        }

        if ($arrData->isNotEmpty()) {
            $arrData = $arrData->shuffle()->slice(0, $limit);
        }

        $arrData = $this->parseDetail($arrData);

        return $arrData;
    }

    public function searchPosts($params)
    {
        $params = array_merge(
            [
            'title' => null,
            'item' => 0,
            'page' => 1,
            ],
            $params
        );

        if (env('USED_ELASTICSEARCH_FLAG', false) == true) {
            $arrData = $this->mysql->elasticSearchPosts($params);
        } else {
            $arrData = $this->mysql->searchPosts($params['title']);
        }

        return $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));
    }

    public function countPost($arrCateId, $pre_cache = false)
    {
        $arrReturn = [];

        foreach (array_wrap($arrCateId) as $category_id) {
            $data = $this->cache->getCountPost($category_id);

            if ((!$this->enable_cache || $pre_cache) || empty($data)) {
                $data = $this->mysql->countPost($category_id);

                if ($this->enable_cache) {
                    $this->cache->setCountPost($category_id, $data);
                }
            }

            $arrReturn[$category_id] = $data;
        }

        if (!is_array($arrCateId)) {
            return $arrReturn[$arrCateId];
        }

        return $arrReturn;
    }

    private function parseDetail($arrData)
    {
        if ($arrData->isNotEmpty()) {
            $arrData->transform(function ($data) {
                $postDetail = $this->getDetailPost($data->id);

                return $postDetail;
            });
        }

        return $arrData;
    }
}
