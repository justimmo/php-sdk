<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Region extends BaseEntity
{
    /**
     * @var Country[]
     * @JUSTIMMO\Relation(path="countries", targetEntity="Justimmo\Api\Entity\Country", multiple=true)
     */
    private $countries;

    /**
     * @var FederalState[]
     * @JUSTIMMO\Relation(path="federalStates", targetEntity="Justimmo\Api\Entity\FederalState", multiple=true)
     */
    private $federalStates;

    /**
     * @var ZipCode[]
     * @JUSTIMMO\Relation(path="zipCodes", targetEntity="Justimmo\Api\Entity\ZipCode", multiple=true)
     */
    private $zipCodes;

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
