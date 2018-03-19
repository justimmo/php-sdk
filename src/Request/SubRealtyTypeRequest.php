<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\SubRealtyType;

/**
 * @method $this withRealtyType()
 * @method $this filterByRealtyType($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class SubRealtyTypeRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const FIELDS = [
        'realtyType'
    ];

    const FILTERS = [
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
