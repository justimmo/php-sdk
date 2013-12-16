<?php

namespace Justimmo\Cache;

class MemcacheCache implements CacheInterface
{
    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * @var int
     */
    private $lifetime = 600;

    public function __construct(\Memcache $memcache, $lifetime = 600)
    {
        $this->memcache = $memcache;
        $this->lifetime = $lifetime;
    }

    /**
     * returns the cache of a key of a key or false if key is not found
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->memcache->get($key);
    }

    /**
     * adds a cache key for a specific lifetime into the cache
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->memcache->set($key, $value, 0, $this->lifetime);
    }

    /**
     * removes a key entry from the cache
     *
     * @param $key
     *
     * @return bool
     */
    public function remove($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     * generates a cache key
     *
     * @param $url
     *
     * @return string
     */
    public function generateCacheKey($url)
    {
        return 'ji_api_' . sha1($url);
    }

}
