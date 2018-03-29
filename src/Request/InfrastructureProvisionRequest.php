<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\InfrastructureProvision;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class InfrastructureProvisionRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/infrastructure-provisions';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return InfrastructureProvision::class;
    }
}
