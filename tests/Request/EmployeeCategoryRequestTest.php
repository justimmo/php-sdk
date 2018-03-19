<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Employee\Category;
use Justimmo\Api\Request\EmployeeCategoryRequest;

class EmployeeCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Category::class;

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

