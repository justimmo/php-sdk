<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Request\RealtyRequest;
use Justimmo\Api\Request\ZipCodeRequest;
use PHPUnit\Framework\TestCase;

class ComplexRequestTest extends TestCase
{
    public function testCombinations()
    {
        $request = new ZipCodeRequest();
        $request
            ->filterByRealties(
                (new RealtyRequest())
                ->filterByPrice(['max' => 2000])
                ->rentable()
            )
            ->sortByZip();

        $this->assertEquals([
            'f' => [
                'realties' => [
                    'price' => ['max' => 2000],
                    'marketingType' => 'rent'
                ],
            ],
            'sort' => 'zip',
        ], $request->getQuery());
    }
}
