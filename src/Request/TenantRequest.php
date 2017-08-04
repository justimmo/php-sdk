<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Tenant;

class TenantRequest implements EntityRequest
{
    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return '/tenant';
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    public function getGuzzleOptions()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Tenant::class;
    }
}
