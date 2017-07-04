<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Feature;

/**
 * @method $this filterByFeatureGroup($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FeatureRequest extends BaseApiRequest
{
    const FIELD_FEATURE_GROUP = 'featureGroup';

    const FILTERS = [
        'featureGroup',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/features';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Feature::class;
    }
}
