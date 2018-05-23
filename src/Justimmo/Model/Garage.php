<?php

namespace Justimmo\Model;

class Garage
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $marketingType;

    /**
     * the net value
     *
     * @var double
     */
    protected $net;

    /**
     * the gross value
     *
     * @var double
     */
    protected $gross;

    /**
     * the vat value
     *
     * @var double
     */
    protected $vat;

    /**
     * the vat type (numeric|percent)
     *
     * @var string
     */
    protected $vatType = 'percent';

    /**
     * the calculated vat value in currency
     *
     * @var double
     */
    protected $vatValue;

    /**
     * @param        $type
     * @param        $name
     * @param null   $gross
     * @param null   $net
     * @param null   $vat
     * @param string $vatType
     * @param null   $vatValue
     */
    public function __construct($type, $name, $quantity = null, $marketingType = 'rent', $gross = null, $net = null, $vat = null, $vatType = 'percent', $vatValue = null)
    {
        $this->type          = $type;
        $this->name          = $name;
        $this->quantity      = $quantity;
        $this->marketingType = $marketingType;
        $this->gross         = $gross;
        $this->net           = $net;
        $this->vat           = $vat;
        $this->vatType       = $vatType;
        $this->vatValue      = $vatValue;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Garage
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Garage
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Garage
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getMarketingType()
    {
        return $this->marketingType;
    }

    /**
     * @param string $marketingType
     *
     * @return Garage
     */
    public function setMarketingType($marketingType)
    {
        $this->marketingType = $marketingType;
        return $this;
    }

    /**
     * @return float
     */
    public function getNet()
    {
        return $this->net;
    }

    /**
     * @param float $net
     * @return Garage
     */
    public function setNet($net)
    {
        $this->net = $net;
        return $this;
    }

    /**
     * @return float
     */
    public function getGross()
    {
        return $this->gross;
    }

    /**
     * @param float $gross
     * @return Garage
     */
    public function setGross($gross)
    {
        $this->gross = $gross;
        return $this;
    }

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     * @return Garage
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return string
     */
    public function getVatType()
    {
        return $this->vatType;
    }

    /**
     * @param string $vatType
     * @return Garage
     */
    public function setVatType($vatType)
    {
        $this->vatType = $vatType;
        return $this;
    }

    /**
     * @return float
     */
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * @param float $vatValue
     * @return Garage
     */
    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;
        return $this;
    }
}
