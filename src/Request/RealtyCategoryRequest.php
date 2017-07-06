<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\RealtyCategory;

/**
 * @method $this filterByWithRealties($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 * @method $this withRealties()
 */
class RealtyCategoryRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withRealties'
    ];

    const FIELDS = [
        'realties'
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/realty-categories';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return RealtyCategory::class;
    }
}
