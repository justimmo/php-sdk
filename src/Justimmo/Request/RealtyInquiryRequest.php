<?php

namespace Justimmo\Request;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Mapper\MapperInterface;

class RealtyInquiryRequest implements RequestInterface
{
    /**
     * @var JustimmoApiInterface
     */
    protected $api;

    /**
     * @var MapperInterface
     */
    protected $mapper;

    protected $realtyId;

    protected $firstName = null;

    protected $lastName = null;

    protected $email = null;

    protected $phone = null;

    protected $message = null;

    protected $street = null;

    protected $zipCode = null;

    protected $city = null;

    protected $country = null;

    public function __construct(JustimmoApiInterface $api, MapperInterface $mapper)
    {
        $this->api    = $api;
        $this->mapper = $mapper;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setEmail($value)
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setFirstName($value)
    {
        $this->firstName = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setLastName($value)
    {
        $this->lastName = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setMessage($value)
    {
        $this->message = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setPhone($value)
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setRealtyId($value)
    {
        $this->realtyId = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRealtyId()
    {
        return $this->realtyId;
    }

    /**
     * @return null
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setZipCode($value)
    {
        $this->zipCode = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setStreet($value)
    {
        $this->street = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setCountry($value)
    {
        $this->country = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setCity($value)
    {
        $this->city = $value;

        return $this;
    }


    public function send()
    {
        $this->api->postRealtyInquiry(array(
            $this->mapper->getFilterPropertyName('realtyId')  => $this->getRealtyId(),
            $this->mapper->getFilterPropertyName('firstName') => $this->getFirstName(),
            $this->mapper->getFilterPropertyName('lastName')  => $this->getLastName(),
            $this->mapper->getFilterPropertyName('email')     => $this->getEmail(),
            $this->mapper->getFilterPropertyName('phone')     => $this->getPhone(),
            $this->mapper->getFilterPropertyName('message')   => $this->getMessage(),
            $this->mapper->getFilterPropertyName('street')    => $this->getStreet(),
            $this->mapper->getFilterPropertyName('zipCode')   => $this->getZipCode(),
            $this->mapper->getFilterPropertyName('city')      => $this->getCity(),
            $this->mapper->getFilterPropertyName('country')   => $this->getCountry(),
        ));
    }
}
