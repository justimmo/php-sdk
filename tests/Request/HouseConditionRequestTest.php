<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty\HouseCondition;
use Justimmo\Api\Request\HouseConditionRequest;

class HouseConditionRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = HouseCondition::class;

    const PATH_PREFIX = '/house-conditions';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new HouseConditionRequest();
    }
}

