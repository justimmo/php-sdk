<?php

namespace Justimmo\Model;

class Zusatzkosten
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var double
     */
    protected $netto;

    /**
     * @var double
     */
    protected $brutto;

    /**
     * @var double
     */
    protected $ust;

    /**
     * @param      $name
     * @param null $brutto
     * @param null $netto
     * @param null $ust
     */
    function __construct($name, $brutto = null, $netto = null, $ust = null)
    {
        $this->brutto = $brutto;
        $this->name   = $name;
        $this->netto  = $netto;
        $this->ust    = $ust;
    }


    /**
     * @param float $brutto
     *
     * @return $this
     */
    public function setBrutto($brutto)
    {
        $this->brutto = $brutto;

        return $this;
    }

    /**
     * @return float
     */
    public function getBrutto()
    {
        return $this->brutto;
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
     * @param float $netto
     *
     * @return $this
     */
    public function setNetto($netto)
    {
        $this->netto = $netto;

        return $this;
    }

    /**
     * @return float
     */
    public function getNetto()
    {
        return $this->netto;
    }

    /**
     * @param float $ust
     *
     * @return $this
     */
    public function setUst($ust)
    {
        $this->ust = $ust;

        return $this;
    }

    /**
     * @return float
     */
    public function getUst()
    {
        return $this->ust;
    }



}