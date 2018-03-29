<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\Feature;
use Justimmo\Api\Entity\Realty\FeatureGroup;
use Justimmo\Api\Request\FeatureGroupRequest;

class FeatureGroupTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/feature_group.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new FeatureGroupRequest();
    }

    /**
     * @inheritdoc
     *
     * @param FeatureGroup $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(FeatureGroup::class, $entity);
        $this->assertEquals(5, $entity->getId());
        $this->assertEquals('Fahrstuhl', $entity->getName());

        $features = $entity->getFeatures();
        $this->assertEquals(2, count($features));

        $this->assertInstanceOf(Feature::class, $features[0]);
        $this->assertEquals(24, $features[0]->getId());
        $this->assertEquals('Lastenaufzug', $features[0]->getName());

        $this->assertInstanceOf(Feature::class, $features[1]);
        $this->assertEquals(25, $features[1]->getId());
        $this->assertEquals('Personenaufzug', $features[1]->getName());
    }
}
