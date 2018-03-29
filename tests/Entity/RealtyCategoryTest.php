<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\Realty;
use Justimmo\Api\Entity\Realty\Category;
use Justimmo\Api\Request\RealtyCategoryRequest;

class RealtyCategoryTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/realty_category.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyCategoryRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Category $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Category::class, $entity);
        $this->assertEquals(2905, $entity->getId());
        $this->assertEquals('Topobjekte', $entity->getName());

        $realties = $entity->getRealties();
        $this->assertEquals(3, count($realties));

        $this->assertInstanceOf(Realty::class, $realties[0]);
        $this->assertEquals(1527378, $realties[0]->getId());

        $this->assertInstanceOf(Realty::class, $realties[1]);
        $this->assertEquals(1182240, $realties[1]->getId());

        $this->assertInstanceOf(Realty::class, $realties[2]);
        $this->assertEquals(1029314, $realties[2]->getId());
    }
}
