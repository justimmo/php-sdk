<?php

namespace Justimmo\Model;

class AdditionalCosts
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var double
     */
    protected $net;

    /**
     * @var double
     */
    protected $gross;

    /**
     * @var double
     */
    protected $vat;

    /**
     * @param      $name
     * @param null $gross
     * @param null $net
     * @param null $vat
     *
     */
    function __construct($name, $gross = null, $net = null, $vat = null)
    {
        $this->gross = $gross;
        $this->name   = $name;
        $this->net    = $net;
        $this->vat    = $vat;
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


}