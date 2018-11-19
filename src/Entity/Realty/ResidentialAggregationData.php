<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * Class ResidentialAggregationData
 * @package Justimmo\Api\Entity\Realty
 *
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class ResidentialAggregationData implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(path="areaFrom", type="float")
     */
    private $areaFrom;
    /**
     * @var float
     * @JUSTIMMO\Column(path="areaTo", type="float")
     */
    private $areaTo;
    /**
     * @var float
     * @JUSTIMMO\Column(path="priceFrom", type="float")
     */
    private $priceFrom;
    /**
     * @var float
     * @JUSTIMMO\Column(path="priceTo", type="float")
     */
    private $priceTo;
    /**
     * @var float
     * @JUSTIMMO\Column(path="roomsFrom", type="float")
     */
    private $roomsFrom;
    /**
     * @var float
     * @JUSTIMMO\Column(path="roomsTo", type="float")
     */
    private $roomsTo;
    /**
     * @var int
     * @JUSTIMMO\Column(path="available", type="integer")
     */
    private $subunitsAvailable;

    /**
     * @return float
     */
    public function getAreaFrom()
    {
        return $this->areaFrom;
    }

    /**
     * @return float
     */
    public function getAreaTo()
    {
        return $this->areaTo;
    }

    /**
     * @return float
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @return float
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @return float
     */
    public function getRoomsFrom()
    {
        return $this->roomsFrom;
    }

    /**
     * @return float
     */
    public function getRoomsTo()
    {
        return $this->roomsTo;
    }

    /**
     * @return int
     */
    public function getSubunitsAvailable()
    {
        return $this->subunitsAvailable;
    }
}
