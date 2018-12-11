<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Price;

/**
 * @JUSTIMMO\Entity()
 */
class Prices implements Entity
{
    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $price;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $priceFrom;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $pricePerM2;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $pricePerM2From;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $purchasePrice;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $rent;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $operatingCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $heatingCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $rentWithoutHeating;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $monthlyCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $transferFee;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $commissionText;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $commissionValue;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $explorationCosts;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $suretyText;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $suretyValue;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $financialContribution;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $feeChargingText;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $feeChargingValue;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $realEstateTax;

    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $landRegistrationCharge;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $contractCharge;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $buildingSubsidies;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $operatingCostsPerM2From;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $operatingCostsPerM2;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $heatingCostsPerM2;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $yield;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $earningsPerYear;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $reserveForRepairs;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $warmWaterCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $furnitureRent;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $coolingCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $parkingCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $garageCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $otherCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $elevaterCosts;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $groupRent;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $groupHeating;

    /**
     * @var Price
     * @JUSTIMMO\Relation(targetEntity="Justimmo\Api\Entity\Price")
     */
    private $groupOther;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $costsExplanation;

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return Price
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @return Price
     */
    public function getPricePerM2()
    {
        return $this->pricePerM2;
    }

    /**
     * @return Price
     */
    public function getPricePerM2From()
    {
        return $this->pricePerM2From;
    }

    /**
     * @return Price
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @return Price
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * @return Price
     */
    public function getOperatingCosts()
    {
        return $this->operatingCosts;
    }

    /**
     * @return Price
     */
    public function getHeatingCosts()
    {
        return $this->heatingCosts;
    }

    /**
     * @return Price
     */
    public function getRentWithoutHeating()
    {
        return $this->rentWithoutHeating;
    }

    /**
     * @return Price
     */
    public function getMonthlyCosts()
    {
        return $this->monthlyCosts;
    }

    /**
     * @return Price
     */
    public function getTransferFee()
    {
        return $this->transferFee;
    }

    /**
     * @return string
     */
    public function getCommissionText()
    {
        return $this->commissionText;
    }

    /**
     * @return Price
     */
    public function getCommissionValue()
    {
        return $this->commissionValue;
    }

    /**
     * @return Price
     */
    public function getExplorationCosts()
    {
        return $this->explorationCosts;
    }

    /**
     * @return string
     */
    public function getSuretyText()
    {
        return $this->suretyText;
    }

    /**
     * @return Price
     */
    public function getSuretyValue()
    {
        return $this->suretyValue;
    }

    /**
     * @return Price
     */
    public function getFinancialContribution()
    {
        return $this->financialContribution;
    }

    /**
     * @return string
     */
    public function getFeeChargingText()
    {
        return $this->feeChargingText;
    }

    /**
     * @return Price
     */
    public function getFeeChargingValue()
    {
        return $this->feeChargingValue;
    }

    /**
     * @return float
     */
    public function getRealEstateTax()
    {
        return $this->realEstateTax;
    }

    /**
     * @return float
     */
    public function getLandRegistrationCharge()
    {
        return $this->landRegistrationCharge;
    }

    /**
     * @return string
     */
    public function getContractCharge()
    {
        return $this->contractCharge;
    }

    /**
     * @return Price
     */
    public function getBuildingSubsidies()
    {
        return $this->buildingSubsidies;
    }

    /**
     * @return Price
     */
    public function getOperatingCostsPerM2From()
    {
        return $this->operatingCostsPerM2From;
    }

    /**
     * @return Price
     */
    public function getOperatingCostsPerM2()
    {
        return $this->operatingCostsPerM2;
    }

    /**
     * @return Price
     */
    public function getHeatingCostsPerM2()
    {
        return $this->heatingCostsPerM2;
    }

    /**
     * @return Price
     */
    public function getYield()
    {
        return $this->yield;
    }

    /**
     * @return Price
     */
    public function getEarningsPerYear()
    {
        return $this->earningsPerYear;
    }

    /**
     * @return Price
     */
    public function getReserveForRepairs()
    {
        return $this->reserveForRepairs;
    }

    /**
     * @return Price
     */
    public function getWarmWaterCosts()
    {
        return $this->warmWaterCosts;
    }

    /**
     * @return Price
     */
    public function getFurnitureRent()
    {
        return $this->furnitureRent;
    }

    /**
     * @return Price
     */
    public function getCoolingCosts()
    {
        return $this->coolingCosts;
    }

    /**
     * @return Price
     */
    public function getParkingCosts()
    {
        return $this->parkingCosts;
    }

    /**
     * @return Price
     */
    public function getGarageCosts()
    {
        return $this->garageCosts;
    }

    /**
     * @return Price
     */
    public function getOtherCosts()
    {
        return $this->otherCosts;
    }

    /**
     * @return Price
     */
    public function getElevaterCosts()
    {
        return $this->elevaterCosts;
    }

    /**
     * @return Price
     */
    public function getGroupRent()
    {
        return $this->groupRent;
    }

    /**
     * @return Price
     */
    public function getGroupHeating()
    {
        return $this->groupHeating;
    }

    /**
     * @return Price
     */
    public function getGroupOther()
    {
        return $this->groupOther;
    }

    /**
     * @return string
     */
    public function getCostsExplanation()
    {
        return $this->costsExplanation;
    }
}
