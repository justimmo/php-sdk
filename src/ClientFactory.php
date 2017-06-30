<?php

namespace Justimmo\Api;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\HandlerStack;
use Justimmo\Api\Authorization\StaticAccessTokenProvider;
use Justimmo\Api\Hydration\EntityHydrator;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;

class ClientFactory
{
    /**
     * Helper function to easyly create a client
     *
     * @param string           $accessToken
     * @param string     $locale
     * @param Cache|null $cache
     *
     * @return Client
     */
    public static function create($accessToken, $locale = 'de', Cache $cache = null)
    {
        if ($cache === null) {
            $cache = new ArrayCache();
            $cache->setNamespace('justimmo:php-sdk:api:');
        }

        $stack = HandlerStack::create();
        $stack->push(new CacheMiddleware(
            new PrivateCacheStrategy(
                new DoctrineCacheStorage($cache)
            )
        ), 'cache');

        $guzzle = new \GuzzleHttp\Client([
            'handler' => $stack,
            'query'   => [
                'locale' => $locale,
            ],
        ]);

        $hydrator = new EntityHydrator(
            new CachedReader(new AnnotationReader(), $cache)
        );

        return new Client(
            $guzzle,
            new StaticAccessTokenProvider($accessToken),
            $hydrator,
            $cache
        );
    }
}
