<?php

namespace Justimmo\Cache;

interface CacheInterface
{
    /**
     * returns the cache of a key of a key or false if key is not found
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * adds a cache key for a specific lifetime into the cache
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value);

    /**
     * removes a key entry from the cache
     *
     * @param $key
     */
    public function remove($key);

    /**
     * generates a cache key
     *
     * @param $url
     *
     * @return string
     */
    public function generateCacheKey($url);
}
