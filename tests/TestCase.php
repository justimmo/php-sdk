<?php

namespace Justimmo\Api\Tests;

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

}
