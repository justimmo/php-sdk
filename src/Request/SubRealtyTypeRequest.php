<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\SubRealtyType;

/**
 * @method $this withRealtyType()
 * @method $this filterByWithRealties($value)
 * @method $this filterByRealtyType($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class SubRealtyTypeRequest extends BaseApiRequest
{
    const FIELDS = [
        'realtyType'
    ];

    const FILTERS = [
        'withRealties',
        'realtyType',
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
