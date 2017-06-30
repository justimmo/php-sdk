<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyType;

class RealtyTypeRequest extends ApiRequest
{
    const FIELD_SUB_REALTY_TYPES = 'subRealtyTypes';

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
    public function getEntityClass()
    {
        return RealtyType::class;
    }
}
