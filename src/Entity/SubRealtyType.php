<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class SubRealtyType implements Entity
{
    use Identifiable, Nameable;

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
