<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Address
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="street", type="string")
     */
    private $street;

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
     * @var string
     * @JUSTIMMO\Column(path="country", type="string")
     */
    private $country;

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
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
