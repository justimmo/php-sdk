<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class RealtyType extends BaseEntity
{
    /**
     * @var SubRealtyType[]
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
