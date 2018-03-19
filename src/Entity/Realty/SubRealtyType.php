<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class SubRealtyType implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var RealtyType
     * @JUSTIMMO\Relation(path="realtyType", targetEntity="Justimmo\Api\Entity\Realty\RealtyType")
     */
    private $realtyType;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return RealtyType
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }
}
