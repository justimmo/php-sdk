<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\HouseCondition;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class HouseConditionRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/house-conditions';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return HouseCondition::class;
    }
}
