<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Address;
use Justimmo\Api\Entity\Country;
use Justimmo\Api\Entity\GeoCoordinates;
use Justimmo\Api\Entity\Tenant;
use Justimmo\Api\Request\TenantRequest;

class TenantTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/tenant.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new TenantRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Tenant $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Tenant::class, $entity);
        $this->assertEquals(7, $entity->getId());
        $this->assertEquals('JUSTIMMO', $entity->getAgency());
        $this->assertEquals('office@justimmo.at', $entity->getEmail());
        $this->assertEquals('+43 1 798 62 05', $entity->getPhone());
        $this->assertEquals('ATU11111111', $entity->getUid());
        $this->assertEquals('2222', $entity->getRegisterNumber());

        $address = $entity->getAddress();
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Mariahilfer Straße 8/11', $address->getStreet());
        $this->assertEquals('1070', $address->getZipCode());
        $this->assertEquals('Wien', $address->getCity());

        $country = $address->getCountry();
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('AT', $country->getId());
        $this->assertEquals('Österreich', $country->getName());

        $geo = $address->getCoordinates();
        $this->assertInstanceOf(GeoCoordinates::class, $geo);
        $this->assertEquals('48.208', $geo->getLatitude());
        $this->assertEquals('16.366', $geo->getLongitude());
    }
}
