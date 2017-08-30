<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Feature;
use Justimmo\Api\Entity\FeatureGroup;
use Justimmo\Api\Request\FeatureRequest;

class FeatureTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/feature.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FeatureRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Feature $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Feature::class, $entity);

        $this->assertEquals(25, $entity->getId());
        $this->assertEquals('Personenaufzug', $entity->getName());

        $group = $entity->getFeatureGroup();

        $this->assertInstanceOf(FeatureGroup::class, $group);
        $this->assertEquals(5, $group->getId());
        $this->assertEquals('Fahrstuhl', $group->getName());

    }
}
