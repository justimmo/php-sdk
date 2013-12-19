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

    public function send()
    {
        $this->api->postRealtyInquiry(array(
            $this->mapper->getFilterPropertyName('realtyId')  => $this->getRealtyId(),
            $this->mapper->getFilterPropertyName('firstName') => $this->getFirstName(),
            $this->mapper->getFilterPropertyName('lastName')  => $this->getLastName(),
            $this->mapper->getFilterPropertyName('email')     => $this->getEmail(),
            $this->mapper->getFilterPropertyName('phone')     => $this->getPhone(),
            $this->mapper->getFilterPropertyName('message')   => $this->getMessage(),
        ));
    }
}
