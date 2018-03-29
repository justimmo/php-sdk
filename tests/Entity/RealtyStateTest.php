<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\RealtyState;
use Justimmo\Api\Request\RealtyStateRequest;

class RealtyStateTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/realty_state.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RealtyStateRequest();
    }

    /**
     * @inheritdoc
     *
     * @param RealtyState $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(RealtyState::class, $entity);
        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('aktiv', $entity->getName());
    }
}
