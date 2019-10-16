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
        $this->assertEquals(1200, $entity->getId());
        $this->assertEquals('1200', $entity->getZip());
    }
}
