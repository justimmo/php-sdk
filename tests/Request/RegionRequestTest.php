<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Geo\Region;
use Justimmo\Api\Request\RegionRequest;

class RegionRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Region::class;

    const PATH_PREFIX = '/regions';

    const FIELDS = [
        'countries',
        'federalStates',
        'zipCodes',
    ];

    const FILTERS = [
        'withRealties',
        'country',
        'federalState',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RegionRequest();
    }
}

