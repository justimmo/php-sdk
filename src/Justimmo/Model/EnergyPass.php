<?php

namespace Justimmo\Model;

class EnergyPass
{
    /**
     * @var string
     */
    protected $epart;

    /**
     * @var \DateTime
     */
    protected $validUntil = null;

    /**
     * @var double
     */
    protected $thermalHeatRequirementValue = null;

    /**
     * @var string
     */
    protected $thermalHeatRequirementClass = null;

    /**
     * @var double
     */
    protected $energyEfficiencyFactorValue = null;

    /**
     * @var string
     */
    protected $energyEfficiencyFactorClass = null;

    /**
     * @var float|null
     */
    protected $finalEnergyDemandValue = null;

    /**
     * @var string|null
     */
    protected $finalEnergyDemandClass = null;

    /**
     * @var bool|null
     */
    protected $finalEnergyDemandClassFossil = null;

    /**
     * @param mixed $epart
     *
     * @return $this
     */
    public function setEpart($epart)
    {
        $this->epart = $epart;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEpart()
    {
        return $this->epart;
    }

    /**
     * @param null $fgeeKlasse
     *
     * @return $this
     */
    public function setEnergyEfficiencyFactorClass($fgeeKlasse)
    {
        $this->energyEfficiencyFactorClass = $fgeeKlasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getEnergyEfficiencyFactorClass()
    {
        return $this->energyEfficiencyFactorClass;
    }

    /**
     * @param null $fgeeWert
     *
     * @return $this
     */
    public function setEnergyEfficiencyFactorValue($fgeeWert)
    {
        $this->energyEfficiencyFactorValue = $fgeeWert;

        return $this;
    }

    /**
     * @return null
     */
    public function getEnergyEfficiencyFactorValue()
    {
        return $this->energyEfficiencyFactorValue;
    }

    /**
     * @param null $gueltigBis
     *
     * @return $this
     */
    public function setValidUntil($gueltigBis)
    {
        $this->validUntil = $gueltigBis;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|null
     */
    public function getValidUntil($format = 'Y-m-d')
    {
        if ($this->validUntil instanceof \DateTime && $format !== null) {
            return $this->validUntil->format($format);
        }

        return $this->validUntil;
    }

    /**
     * @param null $hwbKlasse
     *
     * @return $this
     */
    public function setThermalHeatRequirementClass($hwbKlasse)
    {
        $this->thermalHeatRequirementClass = $hwbKlasse;

        return $this;
    }

    /**
     * @return null
     */
    public function getThermalHeatRequirementClass()
    {
        return $this->thermalHeatRequirementClass;
    }

    /**
     * @param null $hwbWert
     *
     * @return $this
     */
    public function setThermalHeatRequirementValue($hwbWert)
    {
        $this->thermalHeatRequirementValue = $hwbWert;

        return $this;
    }

    /**
     * @return null
     */
    public function getThermalHeatRequirementValue()
    {
        return $this->thermalHeatRequirementValue;
    }

    /**
     * @param float|null $eebWert
     *
     * @return $this
     */
    public function setFinalEnergyDemandValue($eebWert)
    {
        $this->finalEnergyDemandValue = $eebWert;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFinalEnergyDemandValue()
    {
        return $this->finalEnergyDemandValue;
    }

    /**
     * @param string|null $eebKlasse
     *
     * @return $this
     */
    public function setFinalEnergyDemandClass($eebKlasse)
    {
        $this->finalEnergyDemandClass = $eebKlasse;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFinalEnergyDemandClass()
    {
        return $this->finalEnergyDemandClass;
    }

    /**
     * @param bool|null $fossil
     *
     * @return $this
     */
    public function setFinalEnergyDemandClassFossil($fossil)
    {
        $this->finalEnergyDemandClassFossil = $fossil;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFinalEnergyDemandClassFossil()
    {
        return $this->finalEnergyDemandClassFossil;
    }

    /**
     * @return string|null
     */
    public function getFinalEnergyDemandClassWithFossilString()
    {
        if ($this->getFinalEnergyDemandClass() === null) {
            return null;
        }

        return $this->finalEnergyDemandClass . ($this->finalEnergyDemandClassFossil ? ' fossil' : '');
    }
}
