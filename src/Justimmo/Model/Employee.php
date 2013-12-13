<?php

namespace Justimmo\Model;

class Employee
{
    protected $id;

    protected $category = null;

    protected $attachments = array();

    protected $firstName = null;

    protected $lastName = null;

    protected $phone = null;

    protected $email = null;

    protected $position = null;

    protected $mobile = null;

    protected $fax = null;

    protected $title = null;

    /**
     * @param array $attachments
     *
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

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
     * @param null $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $fax
     *
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return null
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param null $handy
     *
     * @return $this
     */
    public function setMobile($handy)
    {
        $this->mobile = $handy;

        return $this;
    }

    /**
     * @return null
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $id
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
     * @param null $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param null $nachname
     *
     * @return $this
     */
    public function setLastName($nachname)
    {
        $this->lastName = $nachname;

        return $this;
    }

    /**
     * @return null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param null $tel
     *
     * @return $this
     */
    public function setPhone($tel)
    {
        $this->phone = $tel;

        return $this;
    }

    /**
     * @return null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param null $vorname
     *
     * @return $this
     */
    public function setFirstName($vorname)
    {
        $this->firstName = $vorname;

        return $this;
    }

    /**
     * @return null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $type
     *
     * @return array
     */
    public function getAttachmentsByType($type)
    {
        $attachments = array();

        /** @var \Justimmo\Model\Attachment $attachment */
        foreach ($this->attachments as $attachment) {
            if ($attachment->getType() == $type) {
                $attachments[] =  $attachment;
            }
        }

        return $attachments;
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
     * @return array
     */
    public function getPictures()
    {
        return $this->getAttachmentsByType('picture');
    }

    /**
     * @param null $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

}
