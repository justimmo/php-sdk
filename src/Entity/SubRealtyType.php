<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class SubRealtyType extends BaseEntity
{
    /**
     * @var RealtyType
     * @JUSTIMMO\Relation(path="realtyType", targetEntity="Justimmo\Api\Entity\RealtyType")
     */
    private $realtyType;

    /**
     * @return RealtyType
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }
}
