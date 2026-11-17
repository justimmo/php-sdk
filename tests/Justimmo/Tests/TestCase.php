<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Model\Project;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\Realty;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected function getFixtures($file)
    {
        return file_get_contents(__DIR__ . '/../../Fixtures/' . $file);
    }

    /**
     * The SDK emits each deprecation notice once per request, so a test which expects one starts
     * from none
     */
    protected static function forgetTheDeprecationNotices(): void
    {
        foreach ([AbstractQuery::class, BasicDataQuery::class, Realty::class, Project::class, JustimmoApi::class] as $class) {
            $notices = new \ReflectionProperty($class, 'deprecationsTriggered');
            $notices->setValue(null, []);
        }
    }
}
