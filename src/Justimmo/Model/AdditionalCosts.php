<?php

namespace Justimmo\Model;

class AdditionalCosts
{
    /**
     * @var string
     */
    protected $name;

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
     * the vat value in percent
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
     * the vat value as inputed by user
     *
     * @var double
     */
    protected $vatInput;

    /**
     * this cost is optional and has not been calculated in the overall values of the realty
     *
     * @var bool
     */
    protected $optional = false;

    /**
     * @param        $name
     * @param null   $gross
     * @param null   $net
     * @param null   $vat
     * @param string $vatType
     * @param null   $vatValue
     * @param null   $vatInput
     * @param bool   $optional
     */
    public function __construct($name, $gross = null, $net = null, $vat = null, $vatType = 'percent', $vatValue = null, $vatInput = null, $optional = false)
    {
        $this->gross = $gross;
        $this->name   = $name;
        $this->net    = $net;
        $this->vat    = $vat;
        $this->vatType = $vatType;
        $this->vatValue = $vatValue;
        $this->vatInput = $vatInput;
        $this->optional = $optional;
    }

    /**
     * @param float $brutto
     *
     * @return $this
     */
    public function setGross($brutto)
    {
        $this->gross = $brutto;

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
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param float $value
     *
     * @return $this
     */
    public function setNet($value)
    {
        $this->net = $value;

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
     * @param float $value
     *
     * @return $this
     */
    public function setVat($value)
    {
        $this->vat = $value;

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
     * @return boolean
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * @param boolean $value
     *
     * @return $this
     */
    public function setOptional($value)
    {
        $this->optional = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getVatInput()
    {
        return $this->vatInput;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function setVatInput($value)
    {
        $this->vatInput = $value;

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
     * @param string $value
     *
     * @return $this
     */
    public function setVatType($value)
    {
        $this->vatType = $value;

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
     * @param float $value
     *
     * @return $this
     */
    public function setVatValue($value)
    {
        $this->vatValue = $value;

        return $this;
    }

}
