<?php

namespace Justimmo\Cache;

/**
 * Class NullCache
 *
 * Class Implements the Cache Interface but does not caches items
 *
 * @package Justimmo\Cache
 */
class NullCache implements CacheInterface
{
    /**
     * returns the cache of a key of a key or false if key is not found
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return false;
    }

    /**
     * adds a cache key for a specific lifetime into the cache
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {

    }

    /**
     * removes a key entry from the cache
     *
     * @param $key
     */
    public function remove($key)
    {

    }
}
