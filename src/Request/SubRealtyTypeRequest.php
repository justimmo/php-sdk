<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\SubRealtyType;

class SubRealtyTypeRequest implements RequestInterface
{
    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return '/sub-realty-types';
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
        return SubRealtyType::class;
    }
}
