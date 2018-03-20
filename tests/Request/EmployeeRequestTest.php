<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Employee\Employee;
use Justimmo\Api\Request\EmployeeRequest;
use Justimmo\Api\Request\RealtyRequest;

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
        'employeeCategories',
    ];

    const SORTS = [
        'name',
        'number',
        'firstName',
        'lastName',
    ];

    const JOINABLE = [
        'realties',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new EmployeeRequest();
    }

    public function testWithRealtyIds()
    {
        $request = $this->getRequest();
        $request->withRealtyIds();
        $this->assertEquals([
            'fields' => 'realtyIds',
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealtyIds(new RealtyRequest());
        $this->assertEquals([
            'fields' => 'realtyIds',
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealtyIds(
            (new RealtyRequest())
                ->filterByRealtyType([1, 2])
                ->filterByFederalState(17)
        );
        $this->assertEquals([
            'fields'     => 'realtyIds',
            'subFilters' => [
                'realtyIds' => [
                    'f' => [
                        'realtyType'   => [1, 2],
                        'federalState' => 17,
                    ],
                ],
            ],
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealtyIds(
            (new RealtyRequest())
                ->filterByRealtyType([1, 2])
                ->filterByFederalState(17)
                ->withNumber()
                ->withTitle()
        );
        $this->assertEquals([
            'fields'     => 'realtyIds',
            'subFilters' => [
                'realtyIds' => [
                    'f'      => [
                        'realtyType'   => [1, 2],
                        'federalState' => 17,
                    ],
                    'fields' => 'number,title',
                ],
            ],
        ], $request->getQuery());
    }
}
