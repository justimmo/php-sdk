<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\EmployeeCategory;

/**
 * @method $this filterByWithEmployees($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class EmployeeCategoryRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withEmployees'
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
        return EmployeeCategory::class;
    }
}
