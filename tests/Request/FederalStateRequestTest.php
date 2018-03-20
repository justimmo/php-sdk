<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Geo\FederalState;
use Justimmo\Api\Request\FederalStateRequest;

class FederalStateRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = FederalState::class;

    const PATH_PREFIX = '/federal-states';

    const FIELDS = [
        'country',
    ];

    const FILTERS = [
        'country',
    ];

    const JOINABLE = [
        'realties',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FederalStateRequest();
    }
}

