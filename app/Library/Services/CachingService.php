<?php
namespace App\Library\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * This is class of cache
 *
 * @author TienDQ
 * When using need : use App\Library\Services\CachingService;
 */
class CachingService
{
    private $_storage = null;
    private static $_instance = null;

    public function __construct($storage = null)
    {
        if (empty($storage)) {
            $storage = config('site.cache.storage');
        }

        $this->_storage = Cache::store($storage);
    }

    final public static function getInstance($storage = null)
    {
        //Check instance
        if (is_null(static::$_instance)) {
            static::$_instance = new self($storage);
        }

        //Return instance
        return static::$_instance;
    }

    /**
     * Retrieving Items from the cache
     * @param string $key
     * @param array $tags
     * @return array or objects
     */
    public function getCache($key, $tags = null)
    {
        if (empty($tags)) {
            return $this->_storage->get($key);
        }

        return $this->_storage->tags((array) $tags)->get($key);
    }

    /**
     * To retrieve all data from the cache.If they don't exist,
     * retrieve them from the database and add them to the cache
     * @param type $key
     * @param type $minutes
     * @param type $sql
     * @return values
     */
    public function getCacheByQuery($key, $minutes, $sql)
    {
        $data = $this->_storage->remember($key, $minutes, function () use ($sql) {
            return DB::select($sql);
        });

        return $data;
    }

    /**
     * Retrieve an item from the cache and then delete it
     * @param type $key
     * @return item, NULL if item does not exist in the cache
     */
    public function getCacheAndDelete($key)
    {
        return $this->_storage->pull($key);
    }

    /**
     * Storing Items In The Cache
     * @param string $key
     * @param mixed $value
     * @param int $times
     */
    public function writeCache($key, $value, $times = null)
    {
        if (empty($times)) {
            $times = config('site.cache.lifetime');
        }

        return $this->_storage->put($key, $value, $times);
    }

    /**
     * Storing Items in the cache permanently
     * @param type $key
     * @param type $value
     * @return boolean
     */
    public function writeCacheForever($key, $value)
    {
        return $this->_storage->forever($key, $value);
    }

    /**
     * Storing Items In The Cache with tags
     * @param string $key
     * @param mixed $value
     * @param mixed $tags
     * @param int $times
     */
    public function writeCacheTags($key, $value, $tags, $times = null)
    {
        if (empty($times)) {
            $times = config('site.cache.lifetime');
        }

        return $this->_storage->tags((array) $tags)->put($key, $value, $times);
    }

    /**
     * Checking For Item Existence
     * @param type $key
     * @return boolean
     */
    public function existKey($key = null)
    {
        return $this->_storage->has($key);
    }

    /**
     * Removing Items From The Cache
     * @param type $key
     * @return boolean
     */
    public function deleteCache($key)
    {
        return $this->_storage->forget($key);
    }

    /**
     * Clear the entire cache
     * @return boolean
     */
    public function deleteAllCache()
    {
        return $this->_storage->flush();
    }
}
