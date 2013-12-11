<?php
namespace Justimmo\Tests;

class V1ObjektListWrapperTest extends \PHPUnit_Framework_TestCase
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
