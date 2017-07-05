<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Country;
use Justimmo\Api\Request\CountryRequest;

class CountryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Country::class;

    const PATH_PREFIX = '/countries';

    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withRealties',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new CountryRequest();
    }
}

