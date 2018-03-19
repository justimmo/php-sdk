<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Geo\Country;
use Justimmo\Api\Entity\Geo\FederalState;
use Justimmo\Api\Request\FederalStateRequest;

class FederalStateTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/federal_state.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FederalStateRequest();
    }

    /**
     * @inheritdoc
     *
     * @param FederalState $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(FederalState::class, $entity);
        $this->assertEquals(134, $entity->getId());
        $this->assertEquals('Wien', $entity->getName());

        $country = $entity->getCountry();
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('AT', $country->getId());
        $this->assertEquals('Österreich', $country->getName());
    }
}
