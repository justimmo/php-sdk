<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Contact\Category;

/**
 * @method $this filterByMarketing($value)
 * @method $this filterByNewsletter($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class ContactCategoryRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'marketing',
        'newsletter',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/contact-categories';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Category::class;
    }
}
