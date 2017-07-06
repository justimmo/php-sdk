<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\EmployeeCategory;
use Justimmo\Api\Request\EmployeeCategoryRequest;

class EmployeeCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = EmployeeCategory::class;

    const PATH_PREFIX = '/employee-categories';

    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withEmployees'
    ];

    const FIELDS = [
        'employees'
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new EmployeeCategoryRequest();
    }
}

