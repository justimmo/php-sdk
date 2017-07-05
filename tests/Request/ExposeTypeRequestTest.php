<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\ExposeType;
use Justimmo\Api\Request\ExposeTypeRequest;

class ExposeTypeRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = ExposeType::class;

    const PATH_PREFIX = '/expose-types';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ExposeTypeRequest();
    }
}

