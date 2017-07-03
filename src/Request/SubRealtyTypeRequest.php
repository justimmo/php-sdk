<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\SubRealtyType;

/**
 * @method $this filterByWithRealties($value)
 * @method $this sortByName($direction = ApiRequest::ASC)
 */
class SubRealtyTypeRequest extends ApiRequest
{
    const FIELD_REALTY_TYPE = 'realtyType';

    const FILTERS = [
        'withRealties',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
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
