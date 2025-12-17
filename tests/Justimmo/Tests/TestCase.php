<?php

namespace Justimmo\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected function getFixtures($file)
    {
        return file_get_contents(__DIR__ . '/../../Fixtures/' . $file);
    }
}
