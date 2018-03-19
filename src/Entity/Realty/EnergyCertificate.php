<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Traits\DateFormatable;

/**
 * @JUSTIMMO\Entity()
 */
class EnergyCertificate implements Entity
{
    use DateFormatable;

    /**
     * @var EnergyCertificateValue
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Realty\EnergyCertificateValue")
     */
    private $thermalHeatRequirement;

    /**
     * @var EnergyCertificateValue
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Realty\EnergyCertificateValue")
     */
    private $energyEfficiencyFactor;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $validUntil;

    /**
     * @return EnergyCertificateValue
     */
    public function getThermalHeatRequirement()
    {
        return $this->thermalHeatRequirement;
    }

    /**
     * @return EnergyCertificateValue
     */
    public function getEnergyEfficiencyFactor()
    {
        return $this->energyEfficiencyFactor;
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getValidUntil($format = null)
    {
        return $this->formatDate($this->validUntil, $format);
    }
}
