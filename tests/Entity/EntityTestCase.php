<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Tests\TestCase;

abstract class EntityTestCase extends TestCase
{
    const FIXTURE_FILE = '{}';

    /**
     * @return \Justimmo\Api\Request\EntityRequest
     */
    abstract protected function getRequest();

    /**
     * Implement assertions for entity
     *
     * @param $entity
     */
    abstract protected function doTestEntity($entity);

    public function testEntity()
    {
        $client = $this->createClient([
            $this->createResponse($this->getFixtures(static::FIXTURE_FILE))
        ]);

        $entity = $client->request($this->getRequest());

        $this->doTestEntity($entity);
    }
}
