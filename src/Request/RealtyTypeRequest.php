<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\RealtyType;

/**
 * @method $this withSubRealtyTypes()
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RealtyTypeRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const FIELDS = [
        'subRealtyTypes',
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
