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
    protected $projectState;

    /**
     * @var string
     */
    protected $zipCode ;

    /**
     * @var string
     */
    protected $place;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $houseNumber;

    /**
     * @var Employee
     */
    protected $contact;

    /**
     * @var array
     */
    protected $attachments = array();

    /**
     * @var Realty[]
     */
    protected $realties = array();

    /**
     * @var string
     */
    protected $freetext1;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @deprecated please use $projectState
     *
     * @var bool
     */
    protected $underConstruction = false;

    /**
     * @var string
     */
    protected $miscellaneous;

    /**
     * @var bool
     */
    protected $isReference;

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setId($value)
    {
        $this->id = $value;

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
     * @param string $value
     *
     * @return $this
     */
    public function setDescription($value)
    {
        $this->description = $value;

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
     * @param string $value
     *
     * @return $this
     */
    public function setTeaser($value)
    {
        $this->teaser = $value;

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
     * @param string $value
     *
     * @return $this
     */
    public function setZipCode($value)
    {
        $this->zipCode = $value;

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
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param Attachment $attachment
     *
     * @return $this
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
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
     * @return Realty[]
     */
    public function getRealties()
    {
        return $this->realties;
    }

    /**
     * @param Realty $realty
     *
     * @return $this
     */
    public function addRealty(Realty $realty)
    {
        $this->realties[] = $realty;

        return $this;
    }

    /**
     * @return int
     */
    public function countRealties()
    {
        return count($this->realties);
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setHouseNumber($value)
    {
        $this->houseNumber = $value;

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
     * @param null $value
     *
     * @return $this
     */
    public function setStreet($value)
    {
        $this->street = $value;

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
     * @param      $type
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
                $attachments[] =  $attachment;
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
     * @param \Justimmo\Model\Employee $value
     *
     * @return $this
     */
    public function setContact($value)
    {
        $this->contact = $value;

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
     * @return string
     */
    public function getMiscellaneous()
    {
        return $this->miscellaneous;
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
     * @deprecated use getProjectState or isStateBuilding
     *
     * @return boolean
     */
    public function getUnderConstruction()
    {
        return $this->underConstruction;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
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
    public function getFreetext1()
    {
        return $this->freetext1;
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
     * Retuns the current project state.
     *
     * @return string
     */
    public function getProjectState()
    {
        return $this->projectState;
    }

    /**
     * @param string $projectState
     *
     * @return Project
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
     * @return bool
     */
    public function getIsReference()
    {
        return $this->isReference;
    }

    /**
     * @param bool $isReference
     *
     * @return Project
     */
    public function setIsReference($isReference)
    {
        $this->isReference = $isReference;

        return $this;
    }
}
