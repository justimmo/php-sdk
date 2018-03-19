<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty\Category;
use Justimmo\Api\Request\RealtyCategoryRequest;

class RealtyCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Category::class;

    const PATH_PREFIX = '/realty-categories';

    const SORTS = [
        'name',
    ];

    const SUB_REQUESTS = [
        'realties',
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

