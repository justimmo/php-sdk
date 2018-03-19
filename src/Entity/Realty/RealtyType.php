<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class RealtyType implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var SubRealtyType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="subRealtyTypes", targetEntity="Justimmo\Api\Entity\Realty\SubRealtyType", multiple=true)
     */
    private $subRealtyTypes;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return SubRealtyType[]
     */
    public function getSubRealtyTypes()
    {
        return $this->subRealtyTypes;
    }
}
