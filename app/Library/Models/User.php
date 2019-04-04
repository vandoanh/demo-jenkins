<?php

namespace App\Library\Models;

use App\Library\Models\Cache\User as CacheUser;
use App\Library\Models\MySql\User as MySqlUser;
use App\Library\Models\Traits\Singleton;

class User
{
    use Singleton;

    private $mysql;
    private $cache;
    private $enable_cache;

    public function __construct()
    {
        $this->mysql = MySqlUser::instance();
        $this->cache = CacheUser::instance();
        $this->enable_cache = config('site.cache.enable');
    }

    /**
     * Function create new user
     * @param array $params
     * @auth: TienDQ
     */
    public function createUser($params)
    {
        $params = array_merge([
            'email' => null,
            'fullname' => null,
            'avatar' => config('constants.image.avatar.name'),
            'gender' => null,
            'birthday' => null,
            'user_type' => config('constants.user.type.member'),
            'password' => null,
        ], $params);

        return $this->mysql->createUser($params);
    }

    /**
     * Function create new user
     * @param int $user_id
     * @param array $params
     * @auth: TienDQ
     */
    public function updateUser($user_id, $params)
    {
        $params = array_filter($params);

        return $this->mysql->updateUser($user_id, $params);
    }

    public function getDetailUserBE($user_id)
    {
        return $this->mysql->getDetailUser($user_id);
    }

    public function getListUserBE($params = [])
    {
        $params = array_merge([
            'fullname' => null,
            'item' => 0,
            'page' => 1,
        ], $params);

        return $this->mysql->getListUserBE($params);
    }

    /**
     * Function get user info by id
     * @param int $user_id
     * @param boolean $pre_cache
     * @auth: TienDQ
     */
    public function getDetailUser($user_id, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailUser($user_id);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailUser($user_id);

            if ($this->enable_cache) {
                $this->cache->setDetailUser($user_id, $arrData);
            }
        }

        return $arrData;
    }

    /**
     * Function get user info by email
     * @param string $email
     * @param boolean $pre_cache
     * @auth: TienDQ
     */
    public function getDetailUserByEmail($email, $pre_cache = false)
    {
        $arrData = $this->cache->getDetailUserByEmail($email);

        if ((!$this->enable_cache || $pre_cache) || !$arrData) {
            $arrData = $this->mysql->getDetailUserByEmail($email);

            if ($this->enable_cache) {
                $this->cache->setDetailUserByEmail($email, $arrData);
            }
        }

        return $arrData;
    }
}
