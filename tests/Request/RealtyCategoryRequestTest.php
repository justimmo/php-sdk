<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty\Category;
use Justimmo\Api\Request\RealtyCategoryRequest;
use Justimmo\Api\Request\RealtyRequest;

class RealtyCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Category::class;

    const PATH_PREFIX = '/realty-categories';

    const SORTS = [
        'name',
    ];

    const JOINABLE = [
        'realties',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyCategoryRequest();
    }

    public function testWithRealties()
    {
        $request = $this->getRequest();
        $request->withRealties();
        $this->assertEquals([
            'fields' => 'realties',
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealties(new RealtyRequest());
        $this->assertEquals([
            'fields' => 'realties',
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealties(
            (new RealtyRequest())
                ->filterByRealtyType([1, 2])
                ->filterByFederalState(17)
        );
        $this->assertEquals([
            'fields'     => 'realties',
            'subFilters' => [
                'realties' => [
                    'f' => [
                        'realtyType'   => [1, 2],
                        'federalState' => 17,
                    ],
                ],
            ],
        ], $request->getQuery());

        $request = $this->getRequest();
        $request->withRealties(
            (new RealtyRequest())
                ->filterByRealtyType([1, 2])
                ->filterByFederalState(17)
                ->withNumber()
                ->withTitle()
        );
        $this->assertEquals([
            'fields'     => 'realties',
            'subFilters' => [
                'realties' => [
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

