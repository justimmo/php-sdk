<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Project;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Tests\MockJustimmoApi;
use Justimmo\Tests\TestCase;

class ProjectQueryTest extends TestCase
{
    private function getQuery($api = null)
    {
        $mapper = new ProjectMapper();
        return new ProjectQuery($api ?: new JustimmoNullApi(), new ProjectWrapper($mapper), $mapper);
    }

    public function testProjectState()
    {
        $query = $this->getQuery();
        $query->filterByProjectState(Project::PROJECT_STATE_BUILDING);
        $this->assertEquals([
            'filter' => [
                'projekt_status' => Project::PROJECT_STATE_BUILDING,
            ]
        ], $query->getParams());

        $query = $this->getQuery();
        $query->filterByProjectState([Project::PROJECT_STATE_PLANNING, Project::PROJECT_STATE_BUILDING]);
        $this->assertEquals([
            'filter' => [
                'projekt_status' => [Project::PROJECT_STATE_PLANNING, Project::PROJECT_STATE_BUILDING]
            ]
        ], $query->getParams());
    }

    public function testFilterByCompletionDate()
    {
        $query = $this->getQuery();
        $query->filterByCompletionDate('2017-01-12');
        $this->assertEquals([
            'filter' => [
                'fertigstellung' => '2017-01-12'
            ]
        ], $query->getParams());

        $query = $this->getQuery();
        $query->filterByCompletionDate(['min' => '2017-01-12', 'max' => '2017-01-15']);
        $this->assertEquals([
            'filter' => [
                'fertigstellung_von' => '2017-01-12',
                'fertigstellung_bis' => '2017-01-15',
            ]
        ], $query->getParams());
    }

    public function testFilterBySaleStart()
    {
        $query = $this->getQuery();
        $query->filterBySaleStart('2017-01-12');
        $this->assertEquals([
            'filter' => [
                'verkaufsstart' => '2017-01-12'
            ]
        ], $query->getParams());

        $query = $this->getQuery();
        $query->filterBySaleStart(['min' => '2017-01-12', 'max' => '2017-01-15']);
        $this->assertEquals([
            'filter' => [
                'verkaufsstart_von' => '2017-01-12',
                'verkaufsstart_bis' => '2017-01-15',
            ]
        ], $query->getParams());
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

    public function testOnlyRealtyIds()
    {
        $query = $this->getQuery();
        $query->onlyRealtyIds(true);
        $this->assertEquals(['objektIds' => 1], $query->getParams());

        $query = $this->getQuery();
        $query->onlyRealtyIds(false);
        $this->assertEquals(['objektIds' => 0], $query->getParams());
    }

    public function testIsReference()
    {
        $query = $this->getQuery();
        $query->filterByIsReference(true);
        $this->assertEquals([
            'filter' => [
                'referenz' => 1,
            ]
        ], $query->getParams());
    }

    /**
     * The api has no filter[land_iso2], so the deprecated method must not build any parameter.
     * Without the explicit method the magic call would send filter[CountryIso2] instead.
     */
    public function testFilterByCountryIso2HasNoEffect()
    {
        $query = $this->getQuery();

        $this->assertSame($query, $query->filterByCountryIso2('AT'));
        $this->assertSame([], $query->getParams());
    }

    public function testFindIds()
    {
        $api = new MockJustimmoApi(['projectIds' => $this->getFixtures('v1/project_ids.json')]);
        $query = $this->getQuery($api);

        $this->assertEquals([
            1,
            2,
            4,
            5,
        ], $query->findIds());
    }

    /**
     * Both methods build filter[tag_name] here too, see RealtyQueryTest
     */
    public function testTagAndRealtyCategoryAccumulate()
    {
        $query = $this->getQuery();
        $query->filterByTag('A')->filterByRealtyCategory('B');

        $this->assertSame(['filter' => ['tag_name' => ['A', 'B']]], $query->getParams());
    }
}
