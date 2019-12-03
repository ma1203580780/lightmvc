<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 19:32
 */
namespace sf\cache;

use Redis;
use Exception;
use sf\base\Component;

/**
 * CacheInterface
 * @author Harry Sun <sunguangjun@126.com>
 */
class RedisCache extends Component implements CacheInterface
{
    /**
     * @var Redis|array the Redis object or the config of redis
     */
    public $redis;

    public function init()
    {
        if (is_array($this->redis)) {
            extract($this->redis);
            $redis = new Redis();
            $redis->connect($host, $port);
            if (!empty($password)) {
                $redis->auth($password);
            }
            $redis->select($database);
            if (!empty($options)) {
                call_user_func_array([$redis, 'setOption'], $options);
            }
            $this->redis = $redis;
        }
        if (!$this->redis instanceof Redis) {
            throw new Exception('Cache::redis must be either a Redis connection instance.');
        }
    }
    /**
     * Builds a normalized cache key from a given key.
     */
    public function buildKey($key)
    {
        if (!is_string($key)) {
            $key = json_encode($key);
        }
        return md5($key);
    }

    /**
     * Retrieves a value from cache with a specified key.
     */
    public function get($key)
    {
        $key = $this->buildKey($key);
        return $this->redis->get($key);
    }

    /**
     * Checks whether a specified key exists in the cache.
     */
    public function exists($key)
    {
        $key = $this->buildKey($key);
        return $this->redis->exists($key);
    }

    /**
     * Retrieves multiple values from cache with the specified keys.
     */
    public function mget($keys)
    {
        for ($index = 0; $index < count($keys); $index++) {
            $keys[$index] = $this->buildKey($keys[$index]);
        }

        return $this->redis->mGet($keys);
    }

    /**
     * Stores a value identified by a key into cache.
     */
    public function set($key, $value, $duration = 0)
    {
        $key = $this->buildKey($key);
        if ($duration !== 0) {
            $expire = (int) $duration * 1000;
            return $this->redis->set($key, $value, $expire);
        } else {
            return $this->redis->set($key, $value);
        }
    }

    /**
     * Stores multiple items in cache. Each item contains a value identified by a key.
     */
    public function mset($items, $duration = 0)
    {
        $failedKeys = [];
        foreach ($items as $key => $value) {
            if ($this->set($key, $value, $duration) === false) {
                $failedKeys[] = $key;
            }
        }

        return $failedKeys;
    }

    /**
     * Stores a value identified by a key into cache if the cache does not contain this key.
     */
    public function add($key, $value, $duration = 0)
    {
        if (!$this->exists($key)) {
            return $this->set($key, $value, $duration);
        } else {
            return false;
        }
    }

    /**
     * Stores multiple items in cache. Each item contains a value identified by a key.
     */
    public function madd($items, $duration = 0)
    {
        $failedKeys = [];
        foreach ($items as $key => $value) {
            if ($this->add($key, $value, $duration) === false) {
                $failedKeys[] = $key;
            }
        }

        return $failedKeys;
    }

    /**
     * Deletes a value with the specified key from cache
     */
    public function delete($key)
    {
        $key = $this->buildKey($key);
        return $this->redis->delete($key);
    }

    /**
     * Deletes all values from cache.
     */
    public function flush()
    {
        return $this->redis->flushDb();
    }
}