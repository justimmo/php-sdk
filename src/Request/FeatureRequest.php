<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\Feature;

/**
 * @method $this withFeatureGroup()
 * @method $this filterByFeatureGroup($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FeatureRequest extends BaseApiRequest
{
    const FIELDS = [
        'featureGroup'
    ];

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
