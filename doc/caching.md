Caching
=======
To speed up your application we strongly suggest you cache all api calls. The JUSTIMMO PHP-SDK comes with a build in Memcache Class. If you don't have a MemcachedService at your disposal you can easily create an inject own Caching Services

Memcache
--------
[PHP Memcache Documentation](http://www.php.net/manual/en/book.memcache.php)

```php
use Justimmo\Cache\MemcacheCache;
use Psr\Log\NullLogger;
use Justimmo\Api\JustimmoApi;

$memcache = new Memcache();
$memcache->addServer('localhost');

//pass an instance of Memcache and a lifetime
$cache = new MemcacheCache($memcache, 1200);
$api = new JustimmoApi('username', 'password', new NullLogger(), $cache);
```

Building your own cache
-----------------------
Create a class which implements the \Justimmo\Cache\CacheInterface
