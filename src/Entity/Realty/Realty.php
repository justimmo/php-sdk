<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Attachment\Attachment;
use Justimmo\Api\Entity\Employee\Employee;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Geo\Address;
use Justimmo\Api\Entity\Attachment\Link;
use Justimmo\Api\Entity\DateFormatable;
use Justimmo\Api\Entity\Identifiable;

/**
 * @JUSTIMMO\Entity()
 * @JUSTIMMO\PreHydrate(moveTo={
 *     "coordinates"="address",
 *     "coordinatesPrecise"="address",
 *     "title"="texts",
 *     "livingArea"="detailedAreas",
 *     "floorArea"="detailedAreas",
 *     "floorAreaFrom"="detailedAreas",
 *     "surfaceArea"="detailedAreas",
 *     "rooms"="detailedRooms",
 *     "price"="detailedPrices",
 *     "priceFrom"="detailedPrices",
 *     "pricePerM2"="detailedPrices",
 *     "pricePerM2From"="detailedPrices"
 * })
 */
class Realty implements Entity
{
    use Identifiable, DateFormatable;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $number;

    /**
     * @var Type
     * @JUSTIMMO\Delegated(targetEntity="\Justimmo\Api\Entity\Realty\Type", targetPath="type")
     */
    private $type;

    /**
     * @var BuildingProgress
     * @JUSTIMMO\Relation(path="buildingProgress", targetEntity="\Justimmo\Api\Entity\Realty\BuildingProgress")
     */
    private $buildingProgress;

    /**
     * @var MarketingType
     * @JUSTIMMO\Delegated(targetEntity="\Justimmo\Api\Entity\Realty\MarketingType", targetPath="type")
     */
    private $marketingType;

    /**
     * @var Occupancy
     * @JUSTIMMO\Delegated(path="occupancy", targetEntity="\Justimmo\Api\Entity\Realty\Occupancy", targetPath="occupancies")
     */
    private $occupancy;

    /**
     * @var RealtyType
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\RealtyType")
     */
    private $realtyType;

    /**
     * @var SubRealtyType
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\SubRealtyType")
     */
    private $subRealtyType;

    /**
     * @var RealtyState
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\RealtyState")
     */
    private $realtyState;

    /**
     * @var MarketingState
     * @JUSTIMMO\Delegated(targetEntity="\Justimmo\Api\Entity\Realty\MarketingState", targetPath="state")
     */
    private $marketingState;

    /**
     * @var Address
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Geo\Address")
     */
    private $address;

    /**
     * @var Areas
     * @JUSTIMMO\Relation(path="detailedAreas", targetEntity="\Justimmo\Api\Entity\Realty\Areas")
     */
    private $areas;

    /**
     * @var Rooms
     * @JUSTIMMO\Relation(path="detailedRooms", targetEntity="\Justimmo\Api\Entity\Realty\Rooms")
     */
    private $rooms;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $showPrices;

    /**
     * @var Prices
     * @JUSTIMMO\Relation(path="detailedPrices", targetEntity="\Justimmo\Api\Entity\Realty\Prices")
     */
    private $prices;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $ownershipType;

    /**
     * @var Texts
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Texts")
     */
    private $texts;

    /**
     * @var Attachment
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment\Attachment")
     */
    private $coverPicture;

    /**
     * @var Feature[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Feature", multiple=true)
     */
    private $features;

    /**
     * @var Employee
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Employee\Employee")
     */
    private $employee;

    /**
     * @var Category[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Category", multiple=true)
     */
    private $categories;

    /**
     * @var EnergyCertificate
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\EnergyCertificate")
     */
    private $energyCertificate;

    /**
     * @var InfrastructureProvision
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\InfrastructureProvision")
     */
    private $infrastructureProvision;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $yearOfConstruction;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $lastRefurbishment;

    /**
     * @var BuildingStyle
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\BuildingStyle")
     */
    private $buildingStyle;

    /**
     * @var Condition
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Condition")
     */
    private $condition;

    /**
     * @var HouseCondition
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\HouseCondition")
     */
    private $houseCondition;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $procuredAt;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $publishedAt;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $saleStart;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $availableFrom;

    /**
     * @var integer
     * @JUSTIMMO\Column(type="integer")
     */
    private $maxRentDuration;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $isReference;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $showInSearch;

    /**
     * @var Attachment[]|\Justimmo\Api\Entity\Attachment\AttachmentCollection
     * @JUSTIMMO\Delegated(targetEntity="\Justimmo\Api\Entity\Attachment\AttachmentCollection", targetPath="attachments")
     */
    private $attachments;

    /**
     * @var Link[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment\Link", multiple=true)
     */
    private $links;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $isReadyForOccupancy;

    /**
     * @var boolean
     * @JUSTIMMO\Column(type="boolean")
     */
    private $isReadyForFinishing;

    /**
     * @var Realty
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Realty")
     */
    private $parent;

    /**
     * @var Realty[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Realty", multiple=true)
     */
    private $children;

    /**
     * @var \Justimmo\Api\Entity\Realty\ResidentialAggregationData
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\ResidentialAggregationData", path="residentialAggregationData")
     */
    private $residentialAggregationData;

    public function __toString()
    {
        return (string) $this->getNumber();
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return BuildingProgress
     */
    public function getBuildingProgress()
    {
        return $this->buildingProgress;
    }

    /**
     * @return MarketingType
     */
    public function getMarketingType()
    {
        return $this->marketingType;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return RealtyType
     */
    public function getRealtyType()
    {
        return $this->realtyType;
    }

    /**
     * @return SubRealtyType
     */
    public function getSubRealtyType()
    {
        return $this->subRealtyType;
    }

    /**
     * @return RealtyState
     */
    public function getRealtyState()
    {
        return $this->realtyState;
    }

    /**
     * @return MarketingState
     */
    public function getMarketingState()
    {
        return $this->marketingState;
    }

    /**
     * @return Occupancy
     */
    public function getOccupancy()
    {
        return $this->occupancy;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Areas
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @return Rooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @return bool
     */
    public function getShowPrices()
    {
        return $this->showPrices;
    }

    /**
     * @return Prices
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return Texts
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @return Attachment
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * @return Feature[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return Category[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getOwnershipType()
    {
        return $this->ownershipType;
    }

    /**
     * @return EnergyCertificate
     */
    public function getEnergyCertificate()
    {
        return $this->energyCertificate;
    }

    /**
     * @return InfrastructureProvision
     */
    public function getInfrastructureProvision()
    {
        return $this->infrastructureProvision;
    }

    /**
     * @return int
     */
    public function getYearOfConstruction()
    {
        return $this->yearOfConstruction;
    }

    /**
     * @return int
     */
    public function getLastRefurbishment()
    {
        return $this->lastRefurbishment;
    }

    /**
     * @return BuildingStyle
     */
    public function getBuildingStyle()
    {
        return $this->buildingStyle;
    }

    /**
     * @return Condition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return HouseCondition
     */
    public function getHouseCondition()
    {
        return $this->houseCondition;
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getProcuredAt($format = null)
    {
        return $this->formatDate($this->procuredAt, $format);
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getPublishedAt($format = null)
    {
        return $this->formatDate($this->publishedAt, $format);
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getCreatedAt($format = null)
    {
        return $this->formatDate($this->createdAt, $format);
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getUpdatedAt($format = null)
    {
        return $this->formatDate($this->updatedAt, $format);
    }

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getSaleStart($format = null)
    {
        return $this->formatDate($this->saleStart, $format);
    }

    /**
     * @return string
     */
    public function getAvailableFrom()
    {
        return $this->availableFrom;
    }

    /**
     * @return int
     */
    public function getMaxRentDuration()
    {
        return $this->maxRentDuration;
    }

    /**
     * @return bool
     */
    public function isReference()
    {
        return $this->isReference;
    }

    /**
     * @return bool
     */
    public function showInSearch()
    {
        return $this->showInSearch;
    }

    /**
     * @return Link[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function isReadyForOccupancy()
    {
        return $this->isReadyForOccupancy;
    }

    /**
     * @return bool
     */
    public function isReadyForFinishing()
    {
        return $this->isReadyForFinishing;
    }

    /**
     * @return Realty
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Realty[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Attachment[]|\Justimmo\Api\Entity\Attachment\AttachmentCollection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return \Justimmo\Api\Entity\Realty\ResidentialAggregationData
     */
    public function getResidentialAggregationData()
    {
        return $this->residentialAggregationData;
    }
}

