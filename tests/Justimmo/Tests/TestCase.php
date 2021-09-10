<?php

namespace Justimmo\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function getFixtures($file)
    {
        return file_get_contents(__DIR__ . '/../../Fixtures/' . $file);
    }
}
