<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\RealtyType;
use Justimmo\Api\Request\RealtyTypeRequest;

class RealtyTypeTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/realty_type.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyTypeRequest();
    }

    /**
     * @inheritdoc
     *
     * @param RealtyType $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(RealtyType::class, $entity);
        $this->assertEquals(11, $entity->getId());
        $this->assertEquals('Freizeitimmobilie gewerblich', $entity->getName());

        $subTypes = $entity->getSubRealtyTypes();

        $this->assertEquals(3, count($subTypes));
        $this->assertEquals(3, count($subTypes));
        $this->assertEquals(66, $subTypes[0]->getId());
        $this->assertEquals('Sportanlagen', $subTypes[0]->getName());
        $this->assertEquals(67, $subTypes[1]->getId());
        $this->assertEquals('Vergnügungsparks / -center', $subTypes[1]->getName());
        $this->assertEquals(150, $subTypes[2]->getId());
        $this->assertEquals('Freizeitanlage', $subTypes[2]->getName());
    }

}
