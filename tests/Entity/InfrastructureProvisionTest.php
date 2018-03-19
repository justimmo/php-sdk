<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Realty\InfrastructureProvision;
use Justimmo\Api\Request\InfrastructureProvisionRequest;

class InfrastructureProvisionTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/infrastructure_provision.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new InfrastructureProvisionRequest();
    }

    /**
     * @inheritdoc
     *
     * @param InfrastructureProvision $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(InfrastructureProvision::class, $entity);
        $this->assertEquals(2, $entity->getId());
        $this->assertEquals('teilerschlossen', $entity->getName());
    }
}
