<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Geo\Country;
use Justimmo\Api\Entity\Geo\FederalState;
use Justimmo\Api\Entity\Geo\Region;
use Justimmo\Api\Entity\Geo\ZipCode;
use Justimmo\Api\Request\ZipCodeRequest;

class ZipCodeTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/zip_code.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ZipCodeRequest();
    }

    /**
     * @inheritdoc
     *
     * @param ZipCode $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(ZipCode::class, $entity);
        $this->assertEquals(1915, $entity->getId());
        $this->assertEquals('1200', $entity->getZip());
        $this->assertEquals('Wien', $entity->getCity());

        $country      = $entity->getCountry();
        $federalState = $entity->getFederalState();
        $region       = $entity->getRegion();

        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('AT', $country->getId());
        $this->assertEquals('Österreich', $country->getName());

        $this->assertInstanceOf(FederalState::class, $federalState);
        $this->assertEquals(134, $federalState->getId());
        $this->assertEquals('Wien', $federalState->getName());

        $this->assertInstanceOf(Region::class, $region);
        $this->assertEquals(7, $region->getId());
        $this->assertEquals('20., Brigittenau', $region->getName());

    }
}
