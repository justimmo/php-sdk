<?php

namespace Justimmo\Api\Tests\Hydrator;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * Class MockedEntity
 *
 * Helper entity to evaluate all possible annotation configurations
 *
 * @JUSTIMMO\Entity
 */
class MockedEntity
{
    /**
     * @var int
     * @JUSTIMMO\Column(type="integer", path="id")
     */
    private $id;

    /**
     * @var string
     * @JUSTIMMO\Column(type="string", path="name")
     */
    private $name;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean", path="active")
     */
    private $active;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float", path="price")
     */
    private $price;

    /**
     * @var float[]
     * @JUSTIMMO\Column(type="float", path="multiFloat", multiple=true)
     */
    private $multiFloat;

    /**
     * @var array
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Tests\Hydrator\MockedEntity", path="subEntities", multiple=true)
     */
    private $subEntities = [];

    /**
     * @var MockedEntity
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Tests\Hydrator\MockedEntity", path="parentEntity")
     */
    private $parentEntity;

    /**
     * @var string
     * @JUSTIMMO\Column(type="string", path={"prefix1", "prefix2", "prefix3"})
     */
    private $complexPath;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public function getSubEntities()
    {
        return $this->subEntities;
    }

    /**
     * @return MockedEntity
     */
    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    /**
     * @return string
     */
    public function getComplexPath()
    {
        return $this->complexPath;
    }

    /**
     * @return float
     */
    public function getMultiFloat()
    {
        return $this->multiFloat;
    }
}
