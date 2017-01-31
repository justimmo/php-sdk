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
        $this->assertEquals(array(
            'filter' => array(
                'projekt_status' => Project::PROJECT_STATE_BUILDING,
            )
        ), $query->getParams());

        $query = $this->getQuery();
        $query->filterByProjectState(array(Project::PROJECT_STATE_PLANNING, Project::PROJECT_STATE_BUILDING));
        $this->assertEquals(array(
            'filter' => array(
                'projekt_status' => array(Project::PROJECT_STATE_PLANNING, Project::PROJECT_STATE_BUILDING)
            )
        ), $query->getParams());
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

    public function testIsReference()
    {
        $query = $this->getQuery();
        $query->filterByIsReference(true);
        $this->assertEquals(array(
            'filter' => array(
                'referenz' => 1,
            )
        ), $query->getParams());
    }

    public function testFindIds()
    {
        $api = new MockJustimmoApi(array('projectIds' => $this->getFixtures('v1/project_ids.json')));
        $query = $this->getQuery($api);

        $this->assertEquals(array(
            1,
            2,
            4,
            5,
        ), $query->findIds());
    }
}
