<?php

namespace Justimmo\Api\Entity\Employee;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Attachment\Attachment;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Geo\Address;
use Justimmo\Api\Entity\Attachment\Link;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class Employee implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var int
     * @JUSTIMMO\Column(path="number", type="integer")
     */
    private $number;

    /**
     * @var string
     * @JUSTIMMO\Column(path="firstName", type="string")
     */
    private $firstName;

    /**
     * @var string
     * @JUSTIMMO\Column(path="lastName", type="string")
     */
    private $lastName;

    /**
     * @var string
     * @JUSTIMMO\Column(path="suffix", type="string")
     */
    private $suffix;

    /**
     * @var string
     * @JUSTIMMO\Column(path="position", type="string")
     */
    private $position;

    /**
     * @var string
     * @JUSTIMMO\Column(path="biography", type="string")
     */
    private $biography;

    /**
     * @var string
     * @JUSTIMMO\Column(path="mobile", type="string")
     */
    private $mobile;

    /**
     * @var string
     * @JUSTIMMO\Column(path="phone", type="string")
     */
    private $phone;

    /**
     * @var string
     * @JUSTIMMO\Column(path="fax", type="string")
     */
    private $fax;

    /**
     * @var string
     * @JUSTIMMO\Column(path="email", type="string")
     */
    private $email;

    /**
     * @var string
     * @JUSTIMMO\Column(path="website", type="string")
     */
    private $website;

    /**
     * @var Address
     * @JUSTIMMO\Relation(path="address", targetEntity="Justimmo\Api\Entity\Geo\Address")
     */
    private $address;

    /**
     * @var Attachment
     * @JUSTIMMO\Relation(path="profilePicture", targetEntity="Justimmo\Api\Entity\Attachment\Attachment")
     */
    private $profilePicture;

    /**
     * @var Attachment[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="pictures", targetEntity="Justimmo\Api\Entity\Attachment\Attachment", multiple=true)
     */
    private $pictures;

    /**
     * @var Category[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="employeeCategories", targetEntity="Justimmo\Api\Entity\Employee\Category", multiple=true)
     */
    private $categories;

    /**
     * @var Link[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="links", targetEntity="Justimmo\Api\Entity\Attachment\Link", multiple=true)
     */
    private $links;

    /**
     * @var int[]
     * @JUSTIMMO\Column(path="realtyIds", type="integer", multiple=true)
     */
    private $realtyIds;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Attachment
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @return Attachment[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @return Category[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Link[]|\Justimmo\Api\ResultSet\ResultSet
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return int[]
     */
    public function getRealtyIds()
    {
        return $this->realtyIds;
    }

}
