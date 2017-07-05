<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Feature;
use Justimmo\Api\Request\FeatureRequest;

class FeatureRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Feature::class;

    const PATH_PREFIX = '/features';

    const SORTS = [
        'name',
    ];

    const FIELDS = [
        'featureGroup'
    ];

    const FILTERS = [
        'featureGroup',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FeatureRequest();
    }
}

