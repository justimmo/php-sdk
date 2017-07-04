<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * Class RealtyType
 *
 * @JUSTIMMO\Entity()
 */
class SubRealtyType
{
    /**
     * @var int
     * @JUSTIMMO\Column(path="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * @JUSTIMMO\Column(path="name")
     */
    private $name;

    /**
     * @var RealtyType
     * @JUSTIMMO\Relation(path="realtyType", targetEntity="Justimmo\Api\Entity\RealtyType")
     */
    private $realtyType;

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
     * @return RealtyType
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }
}
