<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\FeatureGroup;
use Justimmo\Api\Request\FeatureGroupRequest;

class FeatureGroupRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = FeatureGroup::class;

    const PATH_PREFIX = '/feature-groups';

    const SORTS = [
        'name',
    ];

    const FIELDS = [
        'features'
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FeatureGroupRequest();
    }
}

