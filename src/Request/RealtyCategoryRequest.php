<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\Category;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 * @method $this withRealties()
 */
class RealtyCategoryRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const SORTS = [
        'name',
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
        return Category::class;
    }
}
