<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="code")
 */
class Region implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var Country[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="countries", targetEntity="Justimmo\Api\Entity\Geo\Country", multiple=true)
     */
    private $countries;

    /**
     * @var FederalState[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="federalStates", targetEntity="Justimmo\Api\Entity\Geo\FederalState", multiple=true)
     */
    private $federalStates;

    /**
     * @var ZipCode[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="zipCodes", targetEntity="Justimmo\Api\Entity\Geo\ZipCode", multiple=true)
     */
    private $zipCodes;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return Country[]
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @return FederalState[]
     */
    public function getFederalStates()
    {
        return $this->federalStates;
    }

    /**
     * @return ZipCode[]
     */
    public function getZipCodes()
    {
        return $this->zipCodes;
    }
}
