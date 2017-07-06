<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class RealtyRooms implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $rooms;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $bathrooms;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $toilets;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $balconies;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $terraces;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $loggias;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $parkingSpots;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $cellars;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $gardens;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $garages;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $storerooms;

    /**
     * @return float
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @return int
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * @return int
     */
    public function getToilets()
    {
        return $this->toilets;
    }

    /**
     * @return int
     */
    public function getBalconies()
    {
        return $this->balconies;
    }

    /**
     * @return int
     */
    public function getTerraces()
    {
        return $this->terraces;
    }

    /**
     * @return int
     */
    public function getLoggias()
    {
        return $this->loggias;
    }

    /**
     * @return int
     */
    public function getParkingSpots()
    {
        return $this->parkingSpots;
    }

    /**
     * @return int
     */
    public function getCellars()
    {
        return $this->cellars;
    }

    /**
     * @return int
     */
    public function getGardens()
    {
        return $this->gardens;
    }

    /**
     * @return int
     */
    public function getGarages()
    {
        return $this->garages;
    }

    /**
     * @return int
     */
    public function getStorerooms()
    {
        return $this->storerooms;
    }

}
