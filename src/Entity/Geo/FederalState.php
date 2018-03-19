<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class FederalState implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @var Country
     *
     * @JUSTIMMO\Relation(path="country", targetEntity="Justimmo\Api\Entity\Geo\Country")
     */
    private $country;

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
