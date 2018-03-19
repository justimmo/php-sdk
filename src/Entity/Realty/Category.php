<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Category implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var Realty[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Realty\Realty", multiple=true)
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
