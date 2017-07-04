<?php

namespace Justimmo\Api\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Justimmo\Api\Hydration\EntityHydrator;

class TestCase extends \PHPUnit_Framework_TestCase
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
}
