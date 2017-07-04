<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyState;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RealtyStateRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/realty-states';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return RealtyState::class;
    }
}
