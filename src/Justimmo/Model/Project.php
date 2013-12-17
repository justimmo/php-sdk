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



}