<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Project;
use Justimmo\Model\ProjectQuery;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;

class ProjectQueryTest extends \PHPUnit_Framework_TestCase
{
    private function getQuery()
    {
        $mapper = new ProjectMapper();
        return new ProjectQuery(new JustimmoNullApi(), new ProjectWrapper($mapper), $mapper);
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
}
