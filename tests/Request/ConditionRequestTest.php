<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Condition;
use Justimmo\Api\Request\ConditionRequest;

class ConditionRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Condition::class;

    const PATH_PREFIX = '/conditions';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ConditionRequest();
    }
}

