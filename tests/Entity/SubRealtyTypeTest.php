<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\SubRealtyType;
use Justimmo\Api\Request\SubRealtyTypeRequest;

class SubRealtyTypeTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/sub_realty_type.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new SubRealtyTypeRequest();
    }

    /**
     * @inheritdoc
     *
     * @param SubRealtyType $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(SubRealtyType::class, $entity);

        $this->assertEquals(66, $entity->getId());
        $this->assertEquals('Sportanlagen', $entity->getName());

        $this->assertEquals(11, $entity->getRealtyType()->getId());
        $this->assertEquals('Freizeitimmobilie gewerblich', $entity->getRealtyType()->getName());
    }
}
