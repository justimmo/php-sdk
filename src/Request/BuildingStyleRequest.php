<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\BuildingStyle;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class BuildingStyleRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/building-styles';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return BuildingStyle::class;
    }
}
