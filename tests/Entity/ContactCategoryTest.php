<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Contact\Category;
use Justimmo\Api\Request\ContactCategoryRequest;

class ContactCategoryTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/contact_category.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ContactCategoryRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Category $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Category::class, $entity);
        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('Interessent', $entity->getName());
        $this->assertTrue($entity->isMarketing());

    }
}
