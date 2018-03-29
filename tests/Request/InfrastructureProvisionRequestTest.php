<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Realty\InfrastructureProvision;
use Justimmo\Api\Request\InfrastructureProvisionRequest;

class InfrastructureProvisionRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = InfrastructureProvision::class;

    const PATH_PREFIX = '/infrastructure-provisions';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new InfrastructureProvisionRequest();
    }
}

