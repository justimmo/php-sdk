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

    protected function createEntity($content)
    {
        $client = $this->createClient([
            $this->createResponse($content)
        ]);

        return $client->request($this->getRequest());
    }

    public function testEntity()
    {
        $this->doTestEntity($this->createEntity(static::FIXTURE_FILE));
    }
}
