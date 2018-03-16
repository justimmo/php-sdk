<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Address implements Entity
{
    const DISPLAY_RULE_NOTHING = 'nothing';
    const DISPLAY_RULE_CITY    = 'city';
    const DISPLAY_RULE_STREET  = 'street';
    const DISPLAY_RULE_FULL    = 'full';

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
     * @JUSTIMMO\Relation(path="federalState", targetEntity="\Justimmo\Api\Entity\FederalState")
     */
    private $federalState;

    /**
     * @var Country
     * @JUSTIMMO\Relation(path="country", targetEntity="\Justimmo\Api\Entity\Country")
     */
    private $country;

    /**
     * @var string
     * @JUSTIMMO\Column(path="near", type="string")
     */
    private $near;

    /**
     * @var string
     * @JUSTIMMO\Column(path="addressDisplayRule", type="string")
     */
    private $addressDisplayRule;

    /**
     * @var GeoCoordinates
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\GeoCoordinates")
     */
    private $coordinates;

    /**
     * @var GeoCoordinates
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\GeoCoordinatesPrecise")
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
     * @return string
     */
    public function getAddressDisplayRule()
    {
        return $this->addressDisplayRule;
    }

    /**
     * Returns true if the address should be displayed with all its details
     *
     * @return bool
     */
    public function isDisplayFull()
    {
        return $this->addressDisplayRule === self::DISPLAY_RULE_FULL;
    }

    /**
     * Returns true if the address should be completely hidden
     *
     * @return bool
     */
    public function isDisplayNothing()
    {
        return $this->addressDisplayRule === self::DISPLAY_RULE_NOTHING;
    }

    /**
     * Returns true if only city, zip-code and street (without number) should be displayed
     *
     * @return bool
     */
    public function isDisplayStreet()
    {
        return $this->addressDisplayRule === self::DISPLAY_RULE_STREET;
    }

    /**
     * Returns true if only city and zip-code should be displayed
     *
     * @return bool
     */
    public function isDisplayCity()
    {
        return $this->addressDisplayRule === self::DISPLAY_RULE_CITY;
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
