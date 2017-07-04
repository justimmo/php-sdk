<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\ExposeType;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class ExposeTypeRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/expose-types';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return ExposeType::class;
    }
}
