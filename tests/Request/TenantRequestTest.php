<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Tenant;
use Justimmo\Api\Request\TenantRequest;
use PHPUnit\Framework\TestCase;

class TenantRequestTest extends TestCase
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

