<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Geo\ZipCode;
use Justimmo\Api\Request\ZipCodeRequest;

class ZipCodeRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = ZipCode::class;

    const PATH_PREFIX = '/zip-codes';

    const FIELDS = [
        'country',
        'city',
        'federalState',
        'region',
    ];

    const JOINABLE = [
        'realties',
    ];

    const FILTERS = [
        'country',
        'federalState',
    ];

    const SORTS = [
        'zip',
        'city',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ZipCodeRequest();
    }
}

