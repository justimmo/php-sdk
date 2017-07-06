<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class RealtyCategory implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var Realty[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Realty", multiple=true)
     */
    private $realties;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return Realty[]
     */
    public function getRealties()
    {
        return $this->realties;
    }
}
