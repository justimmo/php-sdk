<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\MockJustimmoApi;
use Justimmo\Tests\TestCase;

class RealtyQueryTest extends TestCase
{
    private function getQuery($api = null)
    {
        $mapper = new RealtyMapper();
        return new RealtyQuery(($api ?: new JustimmoNullApi()), new RealtyWrapper($mapper), $mapper);
    }

    public function testAllProjectRealties()
    {
        $query = $this->getQuery();
        $query->allProjectRealties(true);
        $this->assertEquals(array('alleProjektObjekte' => 1), $query->getParams());

        $query = $this->getQuery();
        $query->allProjectRealties(false);
        $this->assertEquals(array('alleProjektObjekte' => 0), $query->getParams());
    }

    public function testFindIds()
    {
        $api = new MockJustimmoApi(array('realtyIds' => $this->getFixtures('v1/realty_ids.json')));
        $query = $this->getQuery($api);

        $this->assertEquals(array(
            920153,
            728584,
            635873,
            587626,
            587622
        ), $query->findIds());
    }
}
