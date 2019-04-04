<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\Notice as MySqlNotice;
use App\Library\Models\Cache\Notice as CacheNotice;
use Carbon\Carbon;
use App\Library\Services\CommonService;

class Notice
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;
    private $max_record_cache = 500;
    private $arrExclude = [];

    public function __construct()
    {
        $this->mysql = MySqlNotice::instance();
        $this->cache = CacheNotice::instance();
        $this->enable_cache = config('site.cache.enable');
    }

    public function setExclude($arrId)
    {
        $this->arrExclude = array_unique(array_merge($this->arrExclude, array_wrap($arrId)));
    }

    /**
     * Code for BE
     */
    public function createNotice($params)
    {
        return $this->mysql->createNotice($params);
    }

    public function updateNotice($params, $id)
    {
        return $this->mysql->updateNotice($params, $id);
    }

    public function deleteNotice($id)
    {
        return $this->mysql->deleteNotice($id);
    }

    public function changeStatus($id)
    {
        return $this->mysql->changeStatus($id);
    }

    public function getDetailNoticeBE($id)
    {
        return $this->mysql->getDetailNoticeBE($id);
    }

    public function getListNoticeBE($params = [])
    {
        $params = array_merge([
            'status' => null,
            'title' => null,
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        if (!empty($params['date_from'])) {
            $params['date_from'] = Carbon::createFromFormat('d/m/Y', $params['date_from'])->format('Y-m-d 0:0:0');
        }
        if (!empty($params['date_from'])) {
            $params['date_to'] = Carbon::createFromFormat('d/m/Y', $params['date_to'])->format('Y-m-d 23:59:59');
        }

        return $this->mysql->getListNoticeBE($params);
    }

    /**
     * Code for FE
     */
    public function getDetailNotice($id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailNotice($id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailNotice($id);

            if ($this->enable_cache) {
                $this->cache->setDetailNotice($id, $arrData);
            }
        }

        return $arrData;
    }

    public function getListNotice($params, $pre_cache = false)
    {
        $params = array_merge([
            'date_from' => null,
            'date_to' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        //default return
        $arrData = collect([]);

        //count total record
        $records = $params['item'] + $params['page'] - 1;

        if ($records < $this->max_record_cache) {
            $arrData = $this->cache->getListNotice();
        }

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListNotice();

            if ($this->enable_cache && $records < $this->max_record_cache) {
                $this->cache->setListNotice($arrData);
            }
        }

        if ($arrData->isNotEmpty()) {
            if (!empty($params['date_from'])) {
                $arrData = $arrData->filter(function ($item) use ($params) {
                    return $item->published_at >= $params['date_from'];
                });
            }

            if (!empty($params['date_to'])) {
                $arrData = $arrData->filter(function ($item) use ($params) {
                    return $item->published_at <= $params[ 'date_to'];
                });
            }
        }

        if ($arrData->isNotEmpty() && !empty($this->arrExclude)) {
            $arrData = $arrData->whereNotIn('id', array_wrap($this->arrExclude));
        }

        $arrData = $this->parseDetail(CommonService::doPaginate($arrData, $params['item'], $params['page']));

        return $arrData;
    }

    private function parseDetail($arrData)
    {
        if ($arrData->isNotEmpty()) {
            $arrData->transform(function ($data) {
                $NoticeDetail = $this->getDetailNotice($data->id);

                return $NoticeDetail;
            });
        }

        return $arrData;
    }
}
