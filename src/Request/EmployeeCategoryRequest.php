<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Employee\Category;

/**
 * @method $this filterByWithEmployees($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 * @method $this withEmployees()
 */
class EmployeeCategoryRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withEmployees'
    ];

    const FIELDS = [
        'employees'
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/employee-categories';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Category::class;
    }
}
