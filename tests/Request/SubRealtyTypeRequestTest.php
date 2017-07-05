<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\SubRealtyType;
use Justimmo\Api\Request\SubRealtyTypeRequest;

class SubRealtyTypeRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = SubRealtyType::class;

    const PATH_PREFIX = '/sub-realty-types';

    const FIELDS = [
        'realtyType'
    ];

    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withRealties',
        'realtyType',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new SubRealtyTypeRequest();
    }
}

