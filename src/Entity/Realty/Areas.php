<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity()
 */
class Areas implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(path="livingArea", type="float")
     */
    private $living;

    /**
     * @var float
     * @JUSTIMMO\Column(path="floorArea", type="float")
     */
    private $floor;

    /**
     * @var float
     * @JUSTIMMO\Column(path="floorAreaFrom", type="float")
     */
    private $floorFrom;

    /**
     * @var float
     * @JUSTIMMO\Column(path="surfaceArea", type="float")
     */
    private $surface;

    /**
     * @var float
     * @JUSTIMMO\Column(path="overallArea", type="float")
     */
    private $overall;

    /**
     * @var float
     * @JUSTIMMO\Column(path="freeArea", type="float")
     */
    private $free;

    /**
     * @var float
     * @JUSTIMMO\Column(path="limitedRentalContractsArea", type="float")
     */
    private $limitedRentalContracts;

    /**
     * @var float
     * @JUSTIMMO\Column(path="storageArea", type="float")
     */
    private $storage;

    /**
     * @var float
     * @JUSTIMMO\Column(path="vendorArea", type="float")
     */
    private $vendor;

    /**
     * @var float
     * @JUSTIMMO\Column(path="officeArea", type="float")
     */
    private $office;

    /**
     * @var float
     * @JUSTIMMO\Column(path="gardenArea", type="float")
     */
    private $garden;

    /**
     * @var float
     * @JUSTIMMO\Column(path="cellarArea", type="float")
     */
    private $cellar;

    /**
     * @var float
     * @JUSTIMMO\Column(path="buildableArea", type="float")
     */
    private $buildable;

    /**
     * @var float
     * @JUSTIMMO\Column(path="loggiaArea", type="float")
     */
    private $loggia;

    /**
     * @var float
     * @JUSTIMMO\Column(path="balconyArea", type="float")
     */
    private $balcony;

    /**
     * @var float
     * @JUSTIMMO\Column(path="terraceArea", type="float")
     */
    private $terrace;

    /**
     * @var float
     * @JUSTIMMO\Column(path="garageArea", type="float")
     */
    private $garage;

    /**
     * @var float
     * @JUSTIMMO\Column(path="parkingArea", type="float")
     */
    private $parking;

    /**
     * @var float
     * @JUSTIMMO\Column(path="undevelopedAtticArea", type="float")
     */
    private $undevelopedAttic;

    /**
     * @return float
     */
    public function getLiving()
    {
        return $this->living;
    }

    /**
     * @return float
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @return float
     */
    public function getFloorFrom()
    {
        return $this->floorFrom;
    }

    /**
     * @return float
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * @return float
     */
    public function getOverall()
    {
        return $this->overall;
    }

    /**
     * @return float
     */
    public function getFree(): float
    {
        return $this->free;
    }

    /**
     * @return float
     */
    public function getLimitedRentalContracts(): float
    {
        return $this->limitedRentalContracts;
    }

    /**
     * @return float
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return float
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return float
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @return float
     */
    public function getGarden()
    {
        return $this->garden;
    }

    /**
     * @return float
     */
    public function getCellar()
    {
        return $this->cellar;
    }

    /**
     * @return float
     */
    public function getBuildable()
    {
        return $this->buildable;
    }

    /**
     * @return float
     */
    public function getLoggia()
    {
        return $this->loggia;
    }

    /**
     * @return float
     */
    public function getBalcony()
    {
        return $this->balcony;
    }

    /**
     * @return float
     */
    public function getTerrace()
    {
        return $this->terrace;
    }

    /**
     * @return float
     */
    public function getGarage()
    {
        return $this->garage;
    }

    /**
     * @return float
     */
    public function getParking()
    {
        return $this->parking;
    }

    /**
     * @return float|null
     */
    public function getUndevelopedAttic(): ?float
    {
        return $this->undevelopedAttic;
    }

}
