<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Geo\ZipCode;
use Justimmo\Api\Request\ZipCodeRequest;

class ZipCodeRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = ZipCode::class;

    const PATH_PREFIX = '/zip-codes';

    const FIELDS = [
    ];

    const JOINABLE = [
        'realties',
    ];

    const FILTERS = [];

    const SORTS = [
        'zip',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ZipCodeRequest();
    }
}

