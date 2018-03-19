<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\Condition;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class ConditionRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/conditions';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Condition::class;
    }
}
