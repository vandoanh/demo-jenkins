<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\Tag as MySqlTag;
use App\Library\Models\Cache\Tag as CacheTag;
use Illuminate\Support\Str;

class Tag
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;

    public function __construct()
    {
        $this->mysql = MySqlTag::instance();
        $this->cache = CacheTag::instance();
        $this->enable_cache = config('site.cache.enable');
    }

    public function createTags($arrTag)
    {
        if (!empty($arrTag)) {
            foreach ($arrTag as $tag) {
                $this->mysql->createTag([
                    'title' => $tag,
                    'status' => config('constants.status.active')
                ]);
            }

            return true;
        }

        return false;
    }

    public function getListTag($pre_cache = false)
    {
        $arrData = $this->cache->getListTag();

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListTag();

            if ($this->enable_cache) {
                $this->cache->setListTag($arrData);
            }
        }

        if ($arrData->isNotEmpty()) {
            $arrData->transform(function ($data) {
                $tagDetail = $this->getDetailTag($data->id);

                return $tagDetail;
            });
        }

        return $arrData;
    }

    public function getDetailTag($id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailTag($id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailTag($id);

            if ($this->enable_cache) {
                $this->cache->setDetailTag($id, $arrData);
            }
        }

        if ($arrData) {
            $arrData->code = Str::slug($arrData->title);
        }

        return $arrData;
    }

    public function getDetailTags($arrTag)
    {
        $arrReturn = [];

        foreach ($arrTag as $tag) {
            $arrReturn[] = $this->getDetailTag($tag);
        }

        return $arrReturn;
    }
}
