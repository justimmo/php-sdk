<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\BuildingStyle;
use Justimmo\Api\Request\BuildingStyleRequest;

class BuildingStyleRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = BuildingStyle::class;

    const PATH_PREFIX = '/building-styles';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new BuildingStyleRequest();
    }
}

