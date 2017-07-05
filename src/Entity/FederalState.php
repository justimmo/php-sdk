<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class FederalState implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var Country
     * @JUSTIMMO\Relation(path="country", targetEntity="Justimmo\Api\Entity\Country")
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
