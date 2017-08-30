<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Country;
use Justimmo\Api\Entity\FederalState;
use Justimmo\Api\Entity\Region;
use Justimmo\Api\Entity\ZipCode;
use Justimmo\Api\Request\RegionRequest;

class RegionTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/region.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new RegionRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Region $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Region::class, $entity);
        $this->assertEquals(7, $entity->getId());
        $this->assertEquals('20., Brigittenau', $entity->getName());

        $countries     = $entity->getCountries();
        $federalStates = $entity->getFederalStates();
        $zipCodes      = $entity->getZipCodes();

        $this->assertEquals(1, count($countries));
        $this->assertEquals(1, count($federalStates));
        $this->assertEquals(2, count($zipCodes));

        $this->assertInstanceOf(Country::class, $countries[0]);
        $this->assertEquals('AT', $countries[0]->getId());
        $this->assertEquals('Österreich', $countries[0]->getName());

        $this->assertInstanceOf(FederalState::class, $federalStates[0]);
        $this->assertEquals(134, $federalStates[0]->getId());
        $this->assertEquals('Wien', $federalStates[0]->getName());

        $this->assertInstanceOf(ZipCode::class, $zipCodes[0]);
        $this->assertEquals(1075, $zipCodes[0]->getId());
        $this->assertEquals('6494', $zipCodes[0]->getZip());

        $this->assertInstanceOf(ZipCode::class, $zipCodes[1]);
        $this->assertEquals(1915, $zipCodes[1]->getId());
        $this->assertEquals('1200', $zipCodes[1]->getZip());
    }
}
