<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\SubRealtyType;

class SubRealtyTypeRequest extends ApiRequest
{
    const FIELD_REALTY_TYPE = 'realtyType';

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
    public function getEntityClass()
    {
        return SubRealtyType::class;
    }
}
