<?php

namespace Justimmo\Model;

class Mitarbeiter
{
    protected $id;

    protected $kategorie = null;

    protected $attachments = array();

    protected $vorname = null;

    protected $nachname = null;

    protected $tel = null;

    protected $email = null;

    protected $position = null;

    protected $handy = null;

    protected $fax = null;

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
    public function setHandy($handy)
    {
        $this->handy = $handy;

        return $this;
    }

    /**
     * @return null
     */
    public function getHandy()
    {
        return $this->handy;
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
     * @param null $kategorie
     *
     * @return $this
     */
    public function setKategorie($kategorie)
    {
        $this->kategorie = $kategorie;

        return $this;
    }

    /**
     * @return null
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }

    /**
     * @param null $nachname
     *
     * @return $this
     */
    public function setNachname($nachname)
    {
        $this->nachname = $nachname;

        return $this;
    }

    /**
     * @return null
     */
    public function getNachname()
    {
        return $this->nachname;
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
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return null
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param null $vorname
     *
     * @return $this
     */
    public function setVorname($vorname)
    {
        $this->vorname = $vorname;

        return $this;
    }

    /**
     * @return null
     */
    public function getVorname()
    {
        return $this->vorname;
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


}
