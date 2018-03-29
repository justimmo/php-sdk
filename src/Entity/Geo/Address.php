<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity()
 */
class Address implements Entity
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="street", type="string")
     */
    private $street;

    /**
     * @var string
     * @JUSTIMMO\Column(path="houseNumber", type="string")
     */
    private $houseNumber;

    /**
     * @var string
     * @JUSTIMMO\Column(path="doorNumber", type="string")
     */
    private $doorNumber;

    /**
     * @var integer
     * @JUSTIMMO\Column(path="floor", type="integer")
     */
    private $floor;

    /**
     * @var string
     * @JUSTIMMO\Column(path="floorDescription", type="string")
     */
    private $floorDescription;

    /**
     * @var string
     * @JUSTIMMO\Column(path="stair", type="string")
     */
    private $stair;

    /**
     * @var string
     * @JUSTIMMO\Column(path="zipCode", type="string")
     */
    private $zipCode;

    /**
     * @var string
     * @JUSTIMMO\Column(path="city", type="string")
     */
    private $city;

    /**
     * @var FederalState
     * @JUSTIMMO\Relation(path="federalState", targetEntity="\Justimmo\Api\Entity\Geo\FederalState")
     */
    private $federalState;

    /**
     * @var Country
     * @JUSTIMMO\Relation(path="country", targetEntity="\Justimmo\Api\Entity\Geo\Country")
     */
    private $country;

    /**
     * @var string
     * @JUSTIMMO\Column(path="near", type="string")
     */
    private $near;

    /**
     * @var AddressDisplayRule
     * @JUSTIMMO\Delegated(path="addressDisplayRule", targetEntity="\Justimmo\Api\Entity\Geo\AddressDisplayRule", targetPath="rule")
     */
    private $displayRule;

    /**
     * @var GeoCoordinates
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Geo\GeoCoordinates")
     */
    private $coordinates;

    /**
     * @var GeoCoordinates
     *
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Geo\GeoCoordinates")
     */
    private $coordinatesPrecise;

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @return string
     */
    public function getDoorNumber()
    {
        return $this->doorNumber;
    }

    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @return string
     */
    public function getFloorDescription()
    {
        return $this->floorDescription;
    }

    /**
     * @return string
     */
    public function getStair()
    {
        return $this->stair;
    }

    /**
     * @return FederalState
     */
    public function getFederalState()
    {
        return $this->federalState;
    }

    /**
     * @return string
     */
    public function getNear()
    {
        return $this->near;
    }

    /**
     * @return AddressDisplayRule
     */
    public function getDisplayRule()
    {
        return $this->displayRule;
    }

    /**
     * @return GeoCoordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return GeoCoordinates
     */
    public function getCoordinatesPrecise()
    {
        return $this->coordinatesPrecise;
    }
}
