<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class RealtyType implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var SubRealtyType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="subRealtyTypes", targetEntity="Justimmo\Api\Entity\SubRealtyType", multiple=true)
     */
    private $subRealtyTypes;

    /**
     * @return SubRealtyType[]
     */
    public function getSubRealtyTypes()
    {
        return $this->subRealtyTypes;
    }
}
