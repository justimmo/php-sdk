<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\HouseCondition;
use Justimmo\Api\Request\HouseConditionRequest;

class HouseConditionTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/house_condition.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new HouseConditionRequest();
    }

    /**
     * @inheritdoc
     *
     * @param HouseCondition $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(HouseCondition::class, $entity);
        $this->assertEquals(2, $entity->getId());
        $this->assertEquals('renovierungsbedürftig', $entity->getName());
    }
}
