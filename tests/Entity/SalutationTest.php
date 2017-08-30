<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Salutation;
use Justimmo\Api\Request\SalutationRequest;

class SalutationTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/salutation.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new SalutationRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Salutation $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Salutation::class, $entity);
        $this->assertEquals(2, $entity->getId());
        $this->assertEquals('Frau', $entity->getName());
    }
}
