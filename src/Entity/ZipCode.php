<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class ZipCode implements Entity
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="id", type="string")
     */
    private $id;

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
     * @JUSTIMMO\Relation(path="federalState", targetEntity="Justimmo\Api\Entity\FederalState")
     */
    private $federalState;

    /**
     * @var Country
     * @JUSTIMMO\Relation(path="country", targetEntity="Justimmo\Api\Entity\Country")
     */
    private $country;

    /**
     * @var Region
     * @JUSTIMMO\Relation(path="region", targetEntity="Justimmo\Api\Entity\Region")
     */
    private $region;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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
