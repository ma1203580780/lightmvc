<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2019/12/3
 * Time: 18:55
 * CacheInterface
 *
    buildKey：构建真正的 key，避免特殊字符影响实现
    get：根据 key 获取缓存的值
    mget：根据 keys 数组获取多个缓存值
    set：根据 key 设置缓存的值
    mset：根据数组设置多个缓存值
    exists：判断 key 是否存在
    add：如果 key 不存在就设置缓存值，否则返回false
    madd：根据数组，判断相应的 key 不存在就设置缓存值
    delete：根据 key 删除一个缓存
    flush：删除所有的缓存
 *
 */

namespace light\cache;

interface CacheInterface
{
    /**
     * Builds a normalized cache key from a given key.
     */
    public function buildKey($key);

    /**
     * Retrieves a value from cache with a specified key.
     */
    public function get($key);

    /**
     * Checks whether a specified key exists in the cache.
     */
    public function exists($key);

    /**
     * Retrieves multiple values from cache with the specified keys.
     */
    public function mget($keys);

    /**
     * Stores a value identified by a key into cache.
     */
    public function set($key, $value, $duration = 0);

    /**
     * Stores multiple items in cache. Each item contains a value identified by a key.
     */
    public function mset($items, $duration = 0);

    /**
     * Stores a value identified by a key into cache if the cache does not contain this key.
     * Nothing will be done if the cache already contains the key.
     */
    public function add($key, $value, $duration = 0);

    /**
     * Stores multiple items in cache. Each item contains a value identified by a key.
     * If the cache already contains such a key, the existing value and expiration time will be preserved.
     */
    public function madd($items, $duration = 0);

    /**
     * Deletes a value with the specified key from cache
     */
    public function delete($key);

    /**
     * Deletes all values from cache.
     */
    public function flush();
}