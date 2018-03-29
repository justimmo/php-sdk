<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class ZipCode implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column(path="zip", type="string")
     */
    private $zip;

    /**
     * @var string
     * @JUSTIMMO\Column(path="city", type="string")
     */
    private $city;

    /**
     * @var FederalState
     * @JUSTIMMO\Relation(path="federalState", targetEntity="Justimmo\Api\Entity\Geo\FederalState")
     */
    private $federalState;

    /**
     * @var Country
     * @JUSTIMMO\Relation(path="country", targetEntity="Justimmo\Api\Entity\Geo\Country")
     */
    private $country;

    /**
     * @var Region
     * @JUSTIMMO\Relation(path="region", targetEntity="Justimmo\Api\Entity\Geo\Region")
     */
    private $region;

    public function __toString()
    {
        return (string) $this->getZip();
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return FederalState
     */
    public function getFederalState()
    {
        return $this->federalState;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }
}
