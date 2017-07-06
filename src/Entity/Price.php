<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Price implements Entity
{
    const VAT_TYPE_NUMERIC = "numeric";
    const VAT_TYPE_PERCENT = "percent";

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $currency = 'EUR';

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $value;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $gross;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $net;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $tare;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $vatInput;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $vatType;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $optional;

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns a value in currency by priority
     *
     * $value - A simple value set by the api if there are no gross/net values
     * $gross - Gross value
     * $net   - Net value
     *
     * @return float
     */
    public function getValue()
    {
        if ($this->value !== null) {
            return $this->value;
        }

        if ($this->gross !== null) {
            return $this->gross;
        }

        return $this->net;
    }

    /**
     * Returns the gross value in currency
     *
     * @return float
     */
    public function getGross()
    {
        return $this->gross;
    }

    /**
     * Return the net value in currency
     *
     * @return float
     */
    public function getNet()
    {
        return $this->net;
    }

    /**
     * The calculated vat value in currency
     *
     * @return float
     */
    public function getTare()
    {
        return $this->tare;
    }

    /**
     * The value the user has entered into the vat field.
     * Can be a percent value or a numeric value. See vatType.
     *
     * @return float
     */
    public function getVatInput()
    {
        return $this->vatInput;
    }

    /**
     * The user has entered the vat as numeric value
     *
     * @return bool
     */
    public function isVatTypeNumeric()
    {
        return $this->vatType === self::VAT_TYPE_NUMERIC;
    }

    /**
     * The user has entered the vat as percent value
     *
     * @return bool
     */
    public function isVatTypePercent()
    {
        return $this->vatType === self::VAT_TYPE_PERCENT;
    }

    /**
     * The optional checkbox for the price is activated. Used mostly in additional costs.
     *
     * @return bool
     */
    public function isOptional()
    {
        return $this->optional;
    }
}
