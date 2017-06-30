<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * Class RealtyType
 *
 * @JUSTIMMO\Entity()
 */
class RealtyType
{
    /**
     * @var int
     * @JUSTIMMO\Column(key="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * @JUSTIMMO\Column(key="name")
     */
    private $name;

    /**
     * @var SubRealtyType[]
     * @JUSTIMMO\Relation(key="subRealtyTypes", targetEntity="Justimmo\Api\Entity\SubRealtyType", multiple=true)
     */
    private $subRealtyTypes;

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
     * @return SubRealtyType[]
     */
    public function getSubRealtyTypes()
    {
        return $this->subRealtyTypes;
    }
}
