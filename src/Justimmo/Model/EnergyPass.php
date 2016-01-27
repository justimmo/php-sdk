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

}
