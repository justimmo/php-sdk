<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\Condition;
use Justimmo\Api\Request\ConditionRequest;

class ConditionTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/condition.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new ConditionRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Condition $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Condition::class, $entity);
        $this->assertEquals(3, $entity->getId());
        $this->assertEquals('neuwertig', $entity->getName());
    }
}
