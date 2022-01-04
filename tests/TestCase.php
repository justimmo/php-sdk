<?php

namespace Justimmo\Api\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Justimmo\Api\Authorization\StaticAccessTokenProvider;
use Justimmo\Api\Client;
use Justimmo\Api\Hydration\EntityHydrator;

class TestCase extends \PHPUnit\Framework\TestCase
{
    const FIXTURES_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures';

    /**
     * Return the contents of a fixtures file
     *
     * @param string $file
     *
     * @return string
     */
    protected function getFixtures($file)
    {
        return file_get_contents(self::FIXTURES_PATH . DIRECTORY_SEPARATOR . $file);
    }

    /**
     * Returns an entity hydrator
     *
     * @return EntityHydrator
     */
    protected function getHydrator()
    {
        return new EntityHydrator(
            new CachedReader(
                new AnnotationReader(),
                new ArrayCache()
            )
        );
    }

    /**
     * @param string $content
     * @param int    $statusCode
     * @param array  $headers
     *
     * @return Response
     */
    protected function createResponse($content, $statusCode = 200, $headers = ['Content-type' => 'application/json'])
    {
        if (file_exists(self::FIXTURES_PATH . DIRECTORY_SEPARATOR . $content)) {
            $content = $this->getFixtures($content);
        }

        return new Response($statusCode, $headers, $content);
    }

    /**
     * Create a mocked api client
     *
     * @param array $responses
     *
     * @return Client
     */
    protected function createClient(array $responses = [])
    {
        return new Client(
            new \GuzzleHttp\Client([
                'handler' => HandlerStack::create(
                    new MockHandler($responses)
                ),
            ]),
            new StaticAccessTokenProvider('Test Token'),
            $this->getHydrator()
        );
    }
}
