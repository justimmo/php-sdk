<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Contact\Category;
use Justimmo\Api\Request\ContactCategoryRequest;

class ContactCategoryRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Category::class;

    const PATH_PREFIX = '/contact-categories';

    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'marketing'
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ContactCategoryRequest();
    }
}

