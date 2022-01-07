<?php

namespace Justimmo\Api;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
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
     * @param string $accessToken
     * @param string $locale
     * @param Reader $annotationReader
     *
     * @return Client
     */
    public static function create($accessToken, $locale = 'de', Reader $annotationReader)
    {
        $doctrineCache = new ArrayCache();
        $doctrineCache->setNamespace('justimmo:php-sdk:api:');

        $stack = HandlerStack::create();
        $stack->push(new CacheMiddleware(
            new PrivateCacheStrategy(
                new DoctrineCacheStorage($doctrineCache)
            )
        ), 'cache');

        $guzzle = new \GuzzleHttp\Client([
            'handler' => $stack,
            'query'   => [
                'locale' => $locale,
            ],
        ]);

        $hydrator = new EntityHydrator($annotationReader);

        return new Client(
            $guzzle,
            new StaticAccessTokenProvider($accessToken),
            $hydrator
        );
    }
}
