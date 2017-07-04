<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\FeatureGroup;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FeatureGroupRequest extends BaseApiRequest
{
    const FIELD_FEATURES = 'features';

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
