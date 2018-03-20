<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Realty\Category;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RealtyCategoryRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const SORTS = [
        'name',
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

    /**
     * Adds the realties field
     *
     * @param RealtyRequest $request Optional filter to be executed on the realties field
     *
     * @return $this
     */
    public function withRealties(RealtyRequest $request = null)
    {
        if ($request !== null) {
            $this->addSubFilter('realties', $request);
        }

        return $this->with('realties');
    }
}
