<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Employee\Employee;
use Justimmo\Api\Request\EmployeeRequest;

class EmployeeRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Employee::class;

    const PATH_PREFIX = '/employees';

    const FIELDS = [
        'name',
        'number',
        'firstName',
        'lastName',
        'suffix',
        'position',
        'biography',
        'mobile',
        'phone',
        'fax',
        'email',
        'address',
        'website',
        'profilePicture',
        'pictures',
        'links',
        'realtyIds',
        'employeeCategories',
    ];

    const SORTS = [
        'name',
        'number',
        'firstName',
        'lastName',
    ];

    const SUB_REQUESTS = [
        'realties',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new EmployeeRequest();
    }
}
