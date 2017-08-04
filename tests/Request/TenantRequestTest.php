<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Tenant;
use Justimmo\Api\Request\TenantRequest;

class TenantRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testParams()
    {
        $request = new TenantRequest();

        $this->assertEquals(Tenant::class, $request->getEntityClass());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals([], $request->getQuery());
        $this->assertEquals([], $request->getGuzzleOptions());
    }
}

