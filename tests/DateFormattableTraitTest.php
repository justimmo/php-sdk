<?php

namespace Justimmo\Api\Tests;

class DateFormattableTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatDate()
    {
        $mock = new DateFormattableMock();

        $this->assertNull($mock->execFormatDate(null, null));
        $this->assertNull($mock->execFormatDate(null, 'Y-m-d'));

        $dateTime = new \DateTime('2017-05-10');
        $this->assertEquals($dateTime, $mock->execFormatDate($dateTime));
        $this->assertEquals('10.05.2017', $mock->execFormatDate($dateTime, 'd.m.Y'));
    }
}
