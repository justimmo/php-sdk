<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyType;

class RealtyTypeRequest implements RequestInterface
{
    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return '/realty-types';
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
    public function getEntityClass()
    {
        return RealtyType::class;
    }
}
