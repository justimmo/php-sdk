<?php

namespace Justimmo\Model;

class Project
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $teaser = null;

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @var string
     */
    protected $zipCode = null;

    /**
     * @var string
     */
    protected $place = null;

    /**
     * @var string
     */
    protected $street = null;

    /**
     * @var string
     */
    protected $houseNumber = null;

    /**
     * @var Employee
     */
    protected $contact = null;

    /**
     * @var array
     */
    protected $attachments = array();

    /**
     * @var array
     */
    protected $realties = array();

    /**
     * @var string
     */
    protected $freetext1 = null;

    /**
     * @var string
     */
    protected $locality = null;

    /**
     * @var bool
     */
    protected $underConstruction = null;

    /**
     * @var string
     */
    protected $miscellaneous = null;

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
     * @return array
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
     */
    public function setMiscellaneous($miscellaneous)
    {
        $this->miscellaneous = $miscellaneous;
    }

    /**
     * @return boolean
     */
    public function getUnderConstruction()
    {
        return $this->underConstruction;
    }

    /**
     * @param boolean $underConstruction
     */
    public function setUnderConstruction($underConstruction)
    {
        $this->underConstruction = $underConstruction;
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
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
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
     */
    public function setFreetext1($freetext1)
    {
        $this->freetext1 = $freetext1;
    }
}
