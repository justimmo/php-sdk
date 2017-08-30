<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\ExposeType;
use Justimmo\Api\Request\ExposeTypeRequest;

class ExposeTypeTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/expose_type.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ExposeTypeRequest();
    }

    /**
     * @inheritdoc
     *
     * @param ExposeType $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(ExposeType::class, $entity);

        $this->assertEquals(570, $entity->getId());
        $this->assertEquals('Angebot (NEU)', $entity->getName());
    }
}
