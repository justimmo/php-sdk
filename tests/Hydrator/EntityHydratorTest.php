<?php

namespace Justimmo\Api\Tests\Hydrator;

use Justimmo\Api\Tests\TestCase;

class EntityHydratorTest extends TestCase
{
    /**
     * @param array $data
     *
     * @return MockedEntity|object
     */
    protected function hydrate(array $data = [])
    {
        return $this->getHydrator()->hydrate($data, MockedEntity::class);
    }

    public function testScalarFields()
    {
        $entity = $this->hydrate([
            'id'     => '15',
            'name'   => 'Test',
            'active' => true,
            'price'  => '17',
        ]);
        $this->assertSame(15, $entity->getId());
        $this->assertSame('Test', $entity->getName());
        $this->assertTrue($entity->isActive());
        $this->assertSame(17.0, $entity->getPrice());

        $entity = $this->hydrate([
            'id'     => '15',
            'active' => 'false',
            'price'  => '17.3',
        ]);
        $this->assertFalse($entity->isActive());
        $this->assertSame(17.3, $entity->getPrice());

        $entity = $this->hydrate([
            'id'     => '15',
            'active' => false,
            'price'  => 17.5,
        ]);
        $this->assertFalse($entity->isActive());
        $this->assertSame(17.5, $entity->getPrice());

        $entity = $this->hydrate([
            'id'     => '15',
            'name'  => ['test', 'test2'],
        ]);
        $this->assertNull($entity->getName());
    }

    public function testRelation()
    {
        // Test multiple
        $entity = $this->hydrate([
            'id'          => 1,
            'name'        => 'Root',
            'subEntities' => [
                [
                    'id'          => 2,
                    'name'        => 'Child 1',
                    'subEntities' => [
                        [
                            'id'   => 4,
                            'name' => 'Grandchild 1',
                        ],
                    ],
                ],
                [
                    'id'   => 3,
                    'name' => 'Child 2',
                ],
            ],
        ]);

        /** @var MockedEntity[] $subEntities */
        $subEntities = $entity->getSubEntities();
        $this->assertSame('Root', $entity->getName());
        $this->assertSame(2, count($subEntities));
        $this->assertSame('Child 1', $subEntities[0]->getName());
        $this->assertSame('Child 2', $subEntities[1]->getName());
        $this->assertSame(0, count($subEntities[1]->getSubEntities()));
        /** @var MockedEntity[] $subSubEntities */
        $subSubEntities = $subEntities[0]->getSubEntities();
        $this->assertSame(1, count($subSubEntities));
        $this->assertSame('Grandchild 1', $subSubEntities[0]->getName());

        // Test single
        $entity = $this->hydrate([
            'id'           => 1,
            'name'         => 'Grandchild',
            'parentEntity' => [
                'id'           => 2,
                'name'         => 'Parent',
                'parentEntity' => [
                    'id'   => 3,
                    'name' => 'Root',
                ],
            ],
        ]);
        $this->assertSame('Grandchild', $entity->getName());
        $this->assertSame('Parent', $entity->getParentEntity()->getName());
        $this->assertSame('Root', $entity->getParentEntity()->getParentEntity()->getName());
        $this->assertNull($entity->getParentEntity()->getParentEntity()->getParentEntity());
    }

    public function testComplexPath()
    {
        $entity = $this->hydrate([
            'id'      => 1,
            'name'    => 'Test',
            'prefix1' => [
                'prefix11' => 'Hurra',
                'prefix2'  => [
                    'prefix3' => 'ComplexTestValue',
                ],
            ],
        ]);
        $this->assertSame('Test', $entity->getName());
        $this->assertSame('ComplexTestValue', $entity->getComplexPath());

        $entity = $this->hydrate([
            'id'      => 1,
            'name'    => 'Test',
            'prefix1' => [
                'prefix11' => 'Hurra',
                'prefix2'  => [
                    'prefix5' => 'ComplexTestValue',
                ],
            ],
        ]);
        $this->assertNull($entity->getComplexPath());

        $entity = $this->hydrate([
            'id'      => 1,
            'name'    => 'Test',
            'prefix1' => [
                'prefix3' => 'Hurra',
            ],
        ]);
        $this->assertNull($entity->getComplexPath());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRelationWrongNotation()
    {
        $this->hydrate([
            'id'          => 1,
            'name'        => 'Root',
            'subEntities' => [
                'id'   => 2,
                'name' => 'Child 1',
            ],
        ]);
    }
}
