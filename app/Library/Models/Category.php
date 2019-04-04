<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\Category as MySqlCategory;
use App\Library\Models\Cache\Category as CacheCategory;

class Category
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;

    public function __construct()
    {
        $this->mysql = MySqlCategory::instance();
        $this->cache = CacheCategory::instance();
        $this->enable_cache = config('site.cache.enable');
    }
    
    public function createCategory($params)
    {
        return $this->mysql->createCategory($params);
    }

    public function updateCategory($params, $id)
    {
        return $this->mysql->updateCategory($params, $id);
    }

    public function deleteCategory($id)
    {
        return $this->mysql->getCategoryDetail($id);
    }

    public function getDetailCategoryBE($id)
    {
        return $this->mysql->getDetailCategory($id);
    }

    public function getListCategoryBE($params = [])
    {
        $params = array_merge([
            'title' => null,
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        return $this->mysql->getListCategoryBE($params);
    }
    
    public function getListParentBE($parent_id = 0)
    {
        return $this->mysql->getListParentBE($parent_id);
    }

    public function getLastOrder($parent_id = 0)
    {
        return $this->mysql->getLastOrder($parent_id);
    }

    public function getFullChildId($parent_id = 0)
    {
        return $this->mysql->getFullChildId($parent_id);
    }

    public function getFullParentId($category_id)
    {
        return $this->mysql->getFullParentId($category_id);
    }

    public function getDetailCategory($id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailCategory($id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailCategory($id);

            if ($this->enable_cache) {
                $this->cache->setDetailCategory($id, $arrData);
            }
        }

        return $arrData;
    }
    
    public function getDetailCategoryByCode($code, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailCategoryByCode($code);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailCategoryByCode($code);

            if ($this->enable_cache) {
                $this->cache->setDetailCategoryByCode($code, $arrData);
            }
        }

        return $arrData;
    }

    public function getListParent($parent_id = 0, $pre_cache = false)
    {
        $arrData = $this->cache->getListParent($parent_id);

        if ((!$this->enable_cache || $pre_cache) || $arrData->isEmpty()) {
            $arrData = $this->mysql->getListParent($parent_id);

            if ($this->enable_cache) {
                $this->cache->setListParent($parent_id, $arrData);
            }
        }

        return $arrData;
    }
}
