<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Geo\Country;
use Justimmo\Api\Request\CountryRequest;

class CountryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Country::class;

    const PATH_PREFIX = '/countries';

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
        return new CountryRequest();
    }
}

