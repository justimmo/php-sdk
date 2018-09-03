<?php

namespace Justimmo\Model;

class Project
{
    const PROJECT_STATE_PLANNING = 'planning';
    const PROJECT_STATE_BUILDING = 'building';
    const PROJECT_STATE_FINISHED = 'finished';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $projectNumber;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $teaser;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $freetext1;

    /**
     * @var string
     */
    protected $freetext2;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @var string
     */
    protected $miscellaneous;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $federalState;

    /**
     * @var string
     */
    protected $place;

    /**
     * @var string
     */
    protected $zipCode;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $houseNumber;

    /**
     * @var string
     */
    protected $proximity;

    /**
     * @var int
     */
    protected $tierCount;

    /**
     * @var int
     */
    protected $atticCount;

    /**
     * @var int
     */
    protected $styleOfBuildingId;

    /**
     * @var string
     */
    protected $yearOfConstruction;

    /**
     * @var string
     */
    protected $noiseLevel;

    /**
     * @var string
     */
    protected $availability;

    /**
     * @var \DateTime
     */
    protected $epcValidUntil;

    /**
     * @var float
     */
    protected $epcHeatingDemand;

    /**
     * @var string
     */
    protected $epcHeatingDemandClass;

    /**
     * @var float
     */
    protected $epcEnergyEfficiencyFactor;

    /**
     * @var string
     */
    protected $epcEnergyEfficiencyFactorClass;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @var string
     */
    protected $houseCondition;

    /**
     * @var string
     */
    protected $areaAssessment;

    /**
     * @var string
     */
    protected $propertyAssessment;

    /**
     * @var array
     */
    protected $occupancy;

    /**
     * @var string
     */
    protected $projectState;

    /**
     * @var string
     */
    protected $projectStateSemantic;

    /**
     * @var bool
     */
    protected $isReference;

    /**
     * @var \DateTime
     */
    protected $completionDate;

    /**
     * @var \DateTime
     */
    protected $saleStart;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var Realty[]
     */
    protected $realties = array();

    /**
     * @var int[]
     */
    protected $realtyIds = array();

    /**
     * @var array
     */
    protected $attachments = array();

    /**
     * @var Employee
     */
    protected $contact;

    /**
     * @deprecated please use $projectState
     *
     * @var bool
     */
    protected $underConstruction = false;

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @var array
     */
    protected $garages = array();

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Project
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Project
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        
        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $projectNumber
     *
     * @return $this
     */
    public function setProjectNumber($projectNumber)
    {
        $this->projectNumber = $projectNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectNumber()
    {
        return $this->projectNumber;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTitle($value)
    {
        $this->title = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $teaser
     *
     * @return $this
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $freetext1
     *
     * @return $this
     */
    public function setFreetext1($freetext1)
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @param string $freetext2
     *
     * @return $this
     */
    public function setFreetext2($freetext2)
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @param string $locality
     *
     * @return $this
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param string $miscellaneous
     *
     * @return $this
     */
    public function setMiscellaneous($miscellaneous)
    {
        $this->miscellaneous = $miscellaneous;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiscellaneous()
    {
        return $this->miscellaneous;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $federalState
     *
     * @return $this
     */
    public function setFederalState($federalState)
    {
        $this->federalState = $federalState;

        return $this;
    }

    /**
     * @return string
     */
    public function getFederalState()
    {
        return $this->federalState;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPlace($value)
    {
        $this->place = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param string $zipCode
     *
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param null $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param null $houseNumber
     *
     * @return $this
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * @return null
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $proximity
     *
     * @return $this
     */
    public function setProximity($proximity)
    {
        $this->proximity = $proximity;

        return $this;
    }

    /**
     * @return string
     */
    public function getProximity()
    {
        return $this->proximity;
    }

    /**
     * @param int $tierCount
     *
     * @return $this
     */
    public function setTierCount($tierCount)
    {
        $this->tierCount = $tierCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getTierCount()
    {
        return $this->tierCount;
    }

    /**
     * @param int $atticCount
     *
     * @return $this
     */
    public function setAtticCount($atticCount)
    {
        $this->atticCount = $atticCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getAtticCount()
    {
        return $this->atticCount;
    }

    /**
     * @param int|null $styleOfBuildingId
     *
     * @return $this
     */
    public function setStyleOfBuildingId($styleOfBuildingId)
    {
        $this->styleOfBuildingId = $styleOfBuildingId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStyleOfBuildingId()
    {
        return $this->styleOfBuildingId;
    }

    /**
     * @param string $yearOfConstruction
     *
     * @return $this
     */
    public function setYearOfConstruction($yearOfConstruction)
    {
        $this->yearOfConstruction = $yearOfConstruction;

        return $this;
    }

    /**
     * @return string
     */
    public function getYearOfConstruction()
    {
        return $this->yearOfConstruction;
    }

    /**
     * @param string $noiseLevel
     *
     * @return $this
     */
    public function setNoiseLevel($noiseLevel)
    {
        $this->noiseLevel = $noiseLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoiseLevel()
    {
        return $this->noiseLevel;
    }

    /**
     * @param string $availability
     *
     * @return $this
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param \DateTime epcValidUntil
     *
     * @return $this
     */
    public function setEpcValidUntil($epcValidUntil)
    {
        $this->epcValidUntil = $epcValidUntil;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getEpcValidUntil($format = 'Y-m-d')
    {
        if ($this->epcValidUntil instanceof \DateTime && $format !== null) {
            return $this->epcValidUntil->format($format);
        }

        return $this->epcValidUntil;
    }

    /**
     * @param float $epcHeatingDemand
     *
     * @return $this
     */
    public function setEpcHeatingDemand($epcHeatingDemand)
    {
        $this->epcHeatingDemand = $epcHeatingDemand;

        return $this;
    }

    /**
     * @return float
     */
    public function getEpcHeatingDemand()
    {
        return $this->epcHeatingDemand;
    }

    /**
     * @param string $epcHeatingDemandClass
     *
     * @return $this
     */
    public function setEpcHeatingDemandClass($epcHeatingDemandClass)
    {
        $this->epcHeatingDemandClass = $epcHeatingDemandClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getEpcHeatingDemandClass()
    {
        return $this->epcHeatingDemandClass;
    }

    /**
     * @param float $epcEnergyEfficiencyFactor
     *
     * @return $this
     */
    public function setEpcEnergyEfficiencyFactor($epcEnergyEfficiencyFactor)
    {
        $this->epcEnergyEfficiencyFactor = $epcEnergyEfficiencyFactor;

        return $this;
    }

    /**
     * @return float
     */
    public function getEpcEnergyEfficiencyFactor()
    {
        return $this->epcEnergyEfficiencyFactor;
    }

    /**
     * @param string $epcEnergyEfficiencyFactorClass
     *
     * @return $this
     */
    public function setEpcEnergyEfficiencyFactorClass($epcEnergyEfficiencyFactorClass)
    {
        $this->epcEnergyEfficiencyFactorClass = $epcEnergyEfficiencyFactorClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getEpcEnergyEfficiencyFactorClass()
    {
        return $this->epcEnergyEfficiencyFactorClass;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $houseCondition
     *
     * @return $this
     */
    public function setHouseCondition($houseCondition)
    {
        $this->houseCondition = $houseCondition;

        return $this;
    }

    /**
     * @return string
     */
    public function getHouseCondition()
    {
        return $this->houseCondition;
    }

    /**
     * @param string $areaAssessment
     *
     * @return $this
     */
    public function setAreaAssessment($areaAssessment)
    {
        $this->areaAssessment = $areaAssessment;

        return $this;
    }

    /**
     * @return string
     */
    public function getAreaAssessment()
    {
        return $this->areaAssessment;
    }

    /**
     * @param string $propertyAssessment
     *
     * @return $this
     */
    public function setPropertyAssessment($propertyAssessment)
    {
        $this->propertyAssessment = $propertyAssessment;

        return $this;
    }

    /**
     * @return string
     */
    public function getPropertyAssessment()
    {
        return $this->propertyAssessment;
    }

    /**
     * @param array $occupancy
     *
     * @return $this
     */
    public function setOccupancy($occupancy)
    {
        $this->occupancy = $occupancy;

        return $this;
    }

    /**
     * @return array
     */
    public function getOccupancy()
    {
        return $this->occupancy;
    }

    /**
     * @param string $projectState
     *
     * @return $this
     */
    public function setProjectState($projectState)
    {
        $this->projectState = $projectState;

        //BC
        if ($projectState === self::PROJECT_STATE_BUILDING) {
            $this->underConstruction = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectState()
    {
        return $this->projectState;
    }

    /**
     * @param string $projectStateSemantic
     *
     * @return $this
     */
    public function setProjectStateSemantic($projectStateSemantic)
    {
        $this->projectStateSemantic = $projectStateSemantic;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectStateSemantic()
    {
        return $this->projectStateSemantic;
    }

    /**
     * @param bool $isReference
     *
     * @return $this
     */
    public function setIsReference($isReference)
    {
        $this->isReference = $isReference;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsReference()
    {
        return $this->isReference;
    }

    /**
     * @param \DateTime $completionDate
     *
     * @return $this
     */
    public function setCompletionDate($completionDate)
    {
        $this->completionDate = $completionDate;

        return $this;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getCompletionDate($format = 'Y-m-d')
    {
        if ($this->completionDate instanceof \DateTime && $format !== null) {
            return $this->completionDate->format($format);
        }

        return $this->completionDate;
    }

    /**
     * @param \DateTime $saleStart
     *
     * @return $this
     */
    public function setSaleStart($saleStart)
    {
        $this->saleStart = $saleStart;
    }

    /**
     * @param string $format formats the date to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getSaleStart($format = 'Y-m-d')
    {
        if ($this->saleStart instanceof \DateTime && $format !== null) {
            return $this->saleStart->format($format);
        }

        return $this->saleStart;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param string $format formats the date time to the specific format, null returns DateTime
     *
     * @return \DateTime|string
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        if ($this->createdAt instanceof \DateTime && $format !== null) {
            return $this->createdAt->format($format);
        }

        return $this->createdAt;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setRealties($value)
    {
        $this->realties = $value;

        return $this;
    }

    /**
     * @param \Justimmo\Model\Realty $realty
     *
     * @return $this
     */
    public function addRealty(Realty $realty)
    {
        $this->realties[] = $realty;

        return $this;
    }

    /**
     * @return \Justimmo\Model\Realty[]
     */
    public function getRealties()
    {
        return $this->realties;
    }

    /**
     * @param \int[] $realtyIds
     *
     * @return $this
     */
    public function setRealtyIds($realtyIds)
    {
        $this->realtyIds = $realtyIds;

        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function addRealtyId($id)
    {
        $this->realtyIds[] = $id;

        return $this;
    }

    /**
     * @return \int[]
     */
    public function getRealtyIds()
    {
        return $this->realtyIds;
    }

    /**
     * @return int
     */
    public function countRealties()
    {
        return count((!empty($this->realties) ? $this->realties : $this->realtyIds));
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setAttachments($value)
    {
        $this->attachments = $value;

        return $this;
    }

    /**
     * @param \Justimmo\Model\Attachment $attachment
     *
     * @return $this
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param                     $type
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getAttachmentsByType($type, $group = false)
    {
        $attachments = array();

        /** @var \Justimmo\Model\Attachment $attachment */
        foreach ($this->attachments as $attachment) {
            if ($attachment->getType() == $type && ($group === false || $group == $attachment->getGroup())) {
                $attachments[] = $attachment;
            }
        }

        return $attachments;
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getPictures($group = false)
    {
        return $this->getAttachmentsByType('picture', $group);
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getVideos($group = false)
    {
        return $this->getAttachmentsByType('video', $group);
    }

    /**
     * @param null|string|boolean $group
     *
     * @return array
     */
    public function getDocuments($group = false)
    {
        return $this->getAttachmentsByType('document', $group);
    }

    /**
     * @param \Justimmo\Model\Employee $contact
     *
     * @return $this
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return \Justimmo\Model\Employee
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @deprecated use getProjectState or isStateBuilding
     *
     * @return boolean
     */
    public function getUnderConstruction()
    {
        return $this->underConstruction;
    }

    /**
     * Returns if the project is finished.
     *
     * @return bool
     */
    public function isStateFinished()
    {
        return $this->getProjectState() === self::PROJECT_STATE_FINISHED;
    }

    /**
     * Retuns if the project is still in planning phase.
     *
     * @return bool
     */
    public function isStatePlanning()
    {
        return $this->getProjectState() === self::PROJECT_STATE_PLANNING;
    }

    /**
     * Returns if the project is currently under construction.
     *
     * @return bool
     */
    public function isStateBuilding()
    {
        return $this->getProjectState() === self::PROJECT_STATE_BUILDING;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setCategories($value)
    {
        $this->categories = $value;

        return $this;
    }

    /**
     * @param $id
     * @param $name
     *
     * @return $this
     */
    public function addCategory($id, $name)
    {
        $this->categories[$id] = $name;

        return $this;
    }

    /**
     * @param array $stellplaetze
     *
     * @return $this
     */
    public function setGarages(array $stellplaetze)
    {
        $this->garages = $stellplaetze;

        return $this;
    }

    /**
     * @param string $key
     * @param Garage $stellplatz
     *
     * @return $this
     */
    public function addGarage($key, Garage $stellplatz)
    {
        $this->garages[$key] = $stellplatz;

        return $this;
    }

    /**
     * @return array
     */
    public function getGarages()
    {
        return $this->garages;
    }
}
