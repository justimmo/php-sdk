<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Country;
use Justimmo\Api\Request\CountryRequest;

class CountryTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/country.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new CountryRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Country $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Country::class, $entity);
        $this->assertEquals('AT', $entity->getId());
        $this->assertEquals('Österreich', $entity->getName());
    }
}
