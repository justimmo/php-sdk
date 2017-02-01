<?php

namespace Justimmo\Tests\Model;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Tests\MockJustimmoApi;
use Justimmo\Tests\TestCase;

class EmployeeQueryTest extends TestCase
{
    private function getQuery($api = null)
    {
        $mapper = new EmployeeMapper();
        return new EmployeeQuery($api ?: new JustimmoNullApi(), new EmployeeWrapper($mapper), $mapper);
    }

    public function testFindIds()
    {
        $api = new MockJustimmoApi(array('employeeIds' => $this->getFixtures('v1/employee_ids.json')));
        $query = $this->getQuery($api);

        $this->assertEquals(array(
            1,
            2,
            4,
            5,
        ), $query->findIds());
    }
}
