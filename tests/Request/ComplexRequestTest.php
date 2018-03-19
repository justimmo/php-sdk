<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Request\RealtyRequest;
use Justimmo\Api\Request\ZipCodeRequest;

class ComplexRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCombinations()
    {
        $request = new ZipCodeRequest();
        $request
            ->filterByCountry(['AT', 'DE'])
            ->filterByFederalState(['min' => 10, 'max' => 25])
            ->filterByRealties(
                (new RealtyRequest())
                ->filterByPrice(['max' => 2000])
                ->rentable()
            )
            ->withCity()
            ->withCountry()
            ->withFederalState()
            ->sortByCity($request::DESC)
            ->sortByZip();

        $this->assertEquals([
            'f' => [
                'country' => ['AT', 'DE'],
                'federalState' => [
                    'min' => 10,
                    'max' => 25,
                ],
                'realties' => [
                    'price' => ['max' => 2000],
                    'marketingType' => 'rent'
                ],
            ],
            'fields' => 'city,country,federalState',
            'sort' => '-city,zip',
        ], $request->getQuery());
    }
}
