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
        $this->assertEquals(['alleProjektObjekte' => 1], $query->getParams());

        $query = $this->getQuery();
        $query->allProjectRealties(false);
        $this->assertEquals(['alleProjektObjekte' => 0], $query->getParams());
    }

    public function testFilterByUpdatedAt()
    {
        $query = $this->getQuery();
        $query->filterByUpdatedAt('2017-01-12');
        $this->assertEquals([
            'filter' => [
                'aktualisiert_am' => '2017-01-12',
            ],
        ], $query->getParams());

        $query = $this->getQuery();
        $query->filterByUpdatedAt(['min' => '2017-01-12', 'max' => '2017-01-15']);
        $this->assertEquals([
            'filter' => [
                'aktualisiert_am_von' => '2017-01-12',
                'aktualisiert_am_bis' => '2017-01-15',
            ],
        ], $query->getParams());
    }

    public function testOrderByUpdatedAt()
    {
        $query = $this->getQuery();
        $query->orderByUpdatedAt('asc');

        $this->assertEquals([
            'orderby'   => 'updated_at',
            'ordertype' => 'asc',
        ], $query->getParams());

        $query->orderByUpdatedAt('desc');

        $this->assertEquals([
            'orderby'   => 'updated_at',
            'ordertype' => 'desc',
        ], $query->getParams());
    }

    public function testFindIds()
    {
        $api   = new MockJustimmoApi(['realtyIds' => $this->getFixtures('v1/realty_ids.json')]);
        $query = $this->getQuery($api);

        $this->assertEquals([
            920153,
            728584,
            635873,
            587626,
            587622,
        ], $query->findIds());
    }

    /**
     * The api requires a list for this filter and rejects a scalar with a 422
     */
    public function testFilterByEquipmentWrapsAScalarInAnArray()
    {
        $query = $this->getQuery();
        $query->filterByEquipment(1);

        $this->assertSame(['filter' => ['objekt_ausstattung_list' => [1]]], $query->getParams());
    }

    public function testFilterByEquipmentLeavesAnArrayAlone()
    {
        $query = $this->getQuery();
        $query->filterByEquipment([1, 2]);

        $this->assertSame(['filter' => ['objekt_ausstattung_list' => [1, 2]]], $query->getParams());
    }

    /**
     * Both methods build filter[tag_name], so the second used to discard the first silently.
     * A single value is still sent as a scalar, so a query which sets it once is unchanged
     */
    public function testTagAndRealtyCategoryAccumulate()
    {
        $query = $this->getQuery();
        $query->filterByTag('Neubau')->filterByRealtyCategory('Wohnung');

        $this->assertSame(
            ['filter' => ['tag_name' => ['Neubau', 'Wohnung']]],
            $query->getParams()
        );
    }

    public function testASingleTagIsStillSentAsAScalar()
    {
        $query = $this->getQuery();
        $query->filterByTag('Neubau');

        $this->assertSame(['filter' => ['tag_name' => 'Neubau']], $query->getParams());
    }

    public function testTheSameTagTwiceIsSentOnce()
    {
        $query = $this->getQuery();
        $query->filterByTag('Neubau')->filterByRealtyCategory('Neubau');

        $this->assertSame(['filter' => ['tag_name' => 'Neubau']], $query->getParams());
    }
}
