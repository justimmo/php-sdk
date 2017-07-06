<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\RealtyCategory;
use Justimmo\Api\Request\RealtyCategoryRequest;

class RealtyCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = RealtyCategory::class;

    const PATH_PREFIX = '/realty-categories';

    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withRealties'
    ];

    const FIELDS = [
        'realties'
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyCategoryRequest();
    }
}

