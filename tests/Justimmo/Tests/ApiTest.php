<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\NullCache;
use Psr\Log\NullLogger;

class ApiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Justimmo\Exception\AuthenticationException
     */
    public function testWrongUserData()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->callObjektList();
    }
}
