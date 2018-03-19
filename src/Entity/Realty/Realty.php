<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Attachment;
use Justimmo\Api\Entity\Employee\Employee;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Geo\Address;
use Justimmo\Api\Entity\Link;
use Justimmo\Api\Entity\Traits\DateFormatable;
use Justimmo\Api\Entity\Traits\Identifiable;

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

    const MARKETING_TYPE_RENT  = 'rent';
    const MARKETING_TYPE_BUY   = 'buy';
    const MARKETING_TYPE_LEASE = 'lease';

    const OCCUPANCY_LIVING     = 'living';
    const OCCUPANCY_COMMERCIAL = 'commercial';
    const OCCUPANCY_INVESTMENT = 'investment';

    const MARKETING_STATE_ACTIVE = 'active';
    const MARKETING_STATE_TEASER = 'teaser';

    const BUILDING_PROGRESS_BUILDING = 'building';
    const BUILDING_PROGRESS_PLANNING = 'planning';
    const BUILDING_PROGRESS_FINISHED = 'finished';

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
     * @var string
     * @JUSTIMMO\Column
     */
    private $buildingProgress;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $marketingType;

    /**
     * @var array
     * @JUSTIMMO\Column(path="occupancy", type="original")
     */
    private $occupancies;

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
     * @var string
     * @JUSTIMMO\Column
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
     * @var Texts
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty\Texts")
     */
    private $texts;

    /**
     * @var Attachment
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment")
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
    private $completionDate;

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
     * @var Attachment[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment", multiple=true)
     */
    private $attachments;

    /**
     * @var Link[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Link", multiple=true)
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
     * Returns true if the realty is still in planning (Only residential projects)
     *
     * @return bool
     */
    public function isBuildingProgressPlanning()
    {
        return $this->buildingProgress === self::BUILDING_PROGRESS_PLANNING;
    }

    /**
     * Returns true if the realty is still in building (Only residential projects)
     *
     * @return bool
     */
    public function isBuildingProgressBuilding()
    {
        return $this->buildingProgress === self::BUILDING_PROGRESS_BUILDING;
    }

    /**
     * Returns true if the realty state is finished (Only residential projects)
     *
     * @return bool
     */
    public function isBuildingProgressFinished()
    {
        return $this->buildingProgress === self::BUILDING_PROGRESS_FINISHED;
    }

    /**
     * @return bool
     */
    public function canBeLeased()
    {
        return $this->marketingType === self::MARKETING_TYPE_LEASE;
    }

    /**
     * @return bool
     */
    public function canBeRented()
    {
        return $this->marketingType === self::MARKETING_TYPE_RENT;
    }

    /**
     * @return bool
     */
    public function canBeBought()
    {
        return $this->marketingType === self::MARKETING_TYPE_BUY;
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
     * @return bool
     */
    public function isMarketingStateActive()
    {
        return $this->marketingState === self::MARKETING_STATE_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isMarketingStateTeaser()
    {
        return $this->marketingState === self::MARKETING_STATE_TEASER;
    }

    /**
     * @return bool
     */
    public function isOccupancyLiving()
    {
        return !empty($this->occupancies[self::OCCUPANCY_LIVING]);
    }

    /**
     * @return bool
     */
    public function isOccupancyCommercial()
    {
        return !empty($this->occupancies[self::OCCUPANCY_COMMERCIAL]);
    }

    /**
     * @return bool
     */
    public function isOccupancyInvestment()
    {
        return !empty($this->occupancies[self::OCCUPANCY_INVESTMENT]);
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
    public function getCompletionDate($format = null)
    {
        return $this->formatDate($this->completionDate, $format);
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
     * Returns attachments of group pictures
     *
     * @return Attachment[]
     */
    public function getPictures()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_PICTURES);
    }

    /**
     * Returns attachments of group plans
     *
     * @return Attachment[]
     */
    public function getPlans()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_PLANS);
    }

    /**
     * Returns attachments of group videos
     *
     * @return Attachment[]
     */
    public function getVideos()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_VIDEOS);
    }

    /**
     * Returns attachments of group documents
     *
     * @return Attachment[]
     */
    public function getDocuments()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_DOCUMENTS);
    }

    /**
     * Returns attachments of group three60 pictures
     *
     * @return Attachment[]
     */
    public function getThree60Pictures()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_THREE60_PICTURES);
    }

    /**
     * Filters attachments by a specific group
     *
     * @param array|int $groups
     *
     * @return Attachment[]
     */
    private function filterAttachmentsByGroup($groups)
    {
        if (!is_array($groups)) {
            $groups = [ $groups ];
        }

        $groupedAttachments = [];
        foreach ($this->attachments as $attachment) {
            if (in_array($attachment->getGroup(), $groups)) {
                $groupedAttachments[] = $attachment;
            }
        }

        return $groupedAttachments;
    }
}

