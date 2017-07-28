<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
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

    /**
     * Describes standalone realties without affiliation to any project
     */
    const TYPE_SIMPLE = 'simple';

    /**
     * Describes a commercial project with multiple sub areas
     */
    const TYPE_COMMERCIAL_PROJECT = 'commercial';

    /**
     * Describes an area (possibly splittable) belonging to a commercial project
     */
    const TYPE_AREA = 'area';

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $number;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $type;

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
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\RealtyType")
     */
    private $realtyType;

    /**
     * @var SubRealtyType
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\SubRealtyType")
     */
    private $subRealtyType;

    /**
     * @var RealtyState
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\RealtyState")
     */
    private $realtyState;

    /**
     * @var Address
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Address")
     */
    private $address;

    /**
     * @var RealtyAreas
     * @JUSTIMMO\Relation(path="detailedAreas", targetEntity="\Justimmo\Api\Entity\RealtyAreas")
     */
    private $areas;

    /**
     * @var RealtyRooms
     * @JUSTIMMO\Relation(path="detailedRooms", targetEntity="\Justimmo\Api\Entity\RealtyRooms")
     */
    private $rooms;

    /**
     * @var RealtyPrices
     * @JUSTIMMO\Relation(path="detailedPrices", targetEntity="\Justimmo\Api\Entity\RealtyPrices")
     */
    private $prices;

    /**
     * @var RealtyTexts
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\RealtyTexts")
     */
    private $texts;

    /**
     * @var Attachment
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment")
     */
    private $coverPicture;

    /**
     * @var Feature[]
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Feature", multiple=true)
     */
    private $features;

    /**
     * @var Employee
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Employee")
     */
    private $employee;

    /**
     * @var RealtyCategory[]
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\RealtyCategory", multiple=true)
     */
    private $realtyCategories;

    /**
     * @var EnergyCertificate
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\EnergyCertificate")
     */
    private $energyCertificate;

    /**
     * @var InfrastructureProvision
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\InfrastructureProvision")
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
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\BuildingStyle")
     */
    private $buildingStyle;

    /**
     * @var Condition
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Condition")
     */
    private $condition;

    /**
     * @var HouseCondition
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\HouseCondition")
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
     * @var Attachment[]
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment", multiple=true)
     */
    private $attachments;

    /**
     * @var Link[]
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Link", multiple=true)
     */
    private $links;

    /**
     * @var Realty
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Realty")
     */
    private $parent;

    public function __toString()
    {
        return (string) $this->getNumber();
    }

    /**
     * Returns true if the realty is a normal standalone realty
     *
     * @return bool
     */
    public function isTypeSimple()
    {
        return $this->type === self::TYPE_SIMPLE;
    }

    /**
     * Returns true if the realty is a big commercial project with subrealties
     *
     * @return bool
     */
    public function isTypeCommercialProject()
    {
        return $this->type === self::TYPE_COMMERCIAL_PROJECT;
    }

    /**
     * Returns true if the realty a partial area belonging to a commercial project
     *
     * @return bool
     */
    public function isTypeArea()
    {
        return $this->type === self::TYPE_AREA;
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
    public function isOccupancyLiving()
    {
        return !empty($this->occupancies['living']);
    }

    /**
     * @return bool
     */
    public function isOccupancyCommercial()
    {
        return !empty($this->occupancies['commercial']);
    }

    /**
     * @return bool
     */
    public function isOccupancyInvestment()
    {
        return !empty($this->occupancies['investment']);
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return RealtyAreas
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @return RealtyRooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @return RealtyPrices
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return RealtyTexts
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
     * @return Feature[]
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
     * @return RealtyCategory[]
     */
    public function getRealtyCategories()
    {
        return $this->realtyCategories;
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
     * @return Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return Realty
     */
    public function getParent()
    {
        return $this->parent;
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
     * @param integer $group
     *
     * @return Attachment[]
     */
    private function filterAttachmentsByGroup($group)
    {
        $groupedAttachments = [];
        foreach ($this->attachments as $attachment) {
            if ($attachment->getGroup() === $group) {
                $groupedAttachments[] = $attachment;
            }
        }

        return $groupedAttachments;
    }
}
