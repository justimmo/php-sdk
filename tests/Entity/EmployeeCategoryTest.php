<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Employee;
use Justimmo\Api\Entity\EmployeeCategory;
use Justimmo\Api\Request\EmployeeCategoryRequest;

class EmployeeCategoryTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/employee_category.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new EmployeeCategoryRequest();
    }

    /**
     * @inheritdoc
     *
     * @param EmployeeCategory $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(EmployeeCategory::class, $entity);

        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('Aussendienst', $entity->getName());

        $employees = $entity->getEmployees();
        $this->assertEquals(2, count($employees));

        $this->assertInstanceOf(Employee::class, $employees[0]);
        $this->assertEquals(2, $employees[0]->getId());
        $this->assertEquals('John Doe', $employees[0]->getName());
        $this->assertEquals('john.doe@justimmo.at', $employees[0]->getEmail());

        $this->assertInstanceOf(Employee::class, $employees[1]);
        $this->assertEquals(3, $employees[1]->getId());
        $this->assertEquals('Jane Doe', $employees[1]->getName());
        $this->assertEquals('jane.doe@justimmo.at', $employees[1]->getEmail());
    }
}
