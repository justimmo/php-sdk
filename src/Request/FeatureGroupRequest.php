<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\FeatureGroup;

/**
 * @method $this withFeatures()
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FeatureGroupRequest extends BaseApiRequest
{
    const FIELDS = [
        'features'
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/feature-groups';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return FeatureGroup::class;
    }
}
