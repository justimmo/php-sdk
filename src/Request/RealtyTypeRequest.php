<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyType;

/**
 * @method $this filterByWithRealties($value)
 */
class RealtyTypeRequest extends ApiRequest
{
    const FIELD_SUB_REALTY_TYPES = 'subRealtyTypes';

    const FILTERS = [
        'withRealties',
    ];

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
