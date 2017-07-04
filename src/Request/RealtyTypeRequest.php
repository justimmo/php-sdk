<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyType;

/**
 * @method $this filterByWithRealties($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RealtyTypeRequest extends BaseApiRequest
{
    const FIELD_SUB_REALTY_TYPES = 'subRealtyTypes';

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
