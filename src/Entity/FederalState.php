<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class FederalState extends BaseEntity
{
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
