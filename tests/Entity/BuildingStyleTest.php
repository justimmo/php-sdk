<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\BuildingStyle;
use Justimmo\Api\Request\BuildingStyleRequest;

class BuildingStyleTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/building_style.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new BuildingStyleRequest();
    }

    /**
     * @inheritdoc
     *
     * @param BuildingStyle $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(BuildingStyle::class, $entity);
        $this->assertEquals(2, $entity->getId());
        $this->assertEquals('Neubau', $entity->getName());
    }
}
