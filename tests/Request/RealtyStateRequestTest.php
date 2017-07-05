<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\RealtyState;
use Justimmo\Api\Request\RealtyStateRequest;

class RealtyStateRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = RealtyState::class;

    const PATH_PREFIX = '/realty-states';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyStateRequest();
    }
}

