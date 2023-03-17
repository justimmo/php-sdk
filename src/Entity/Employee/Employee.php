<?php

namespace Justimmo\Api\Entity\Employee;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Attachment\Attachment;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Geo\Address;
use Justimmo\Api\Entity\Attachment\Link;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;
use Justimmo\Api\Entity\ParsedUrl;
use Justimmo\Api\ResultSet\ResultSet;

/**
 * @JUSTIMMO\Entity()
 */
class Employee implements Entity
{
    use Identifiable, Nameable;

    /**
     * @JUSTIMMO\Column(path="number", type="integer")
     */
    private $number;

    /**
     * @JUSTIMMO\Column(path="firstName", type="string")
     */
    private string $firstName = '';

    /**
     * @JUSTIMMO\Column(path="lastName", type="string")
     */
    private string $lastName = '';

    /**
     * @JUSTIMMO\Column(path="suffix", type="string")
     */
    private string $suffix = '';

    /**
     * @JUSTIMMO\Column(path="position", type="string")
     */
    private string $position = '';

    /**
     * @JUSTIMMO\Column(path="biography", type="string")
     */
    private string $biography = '';

    /**
     * @JUSTIMMO\Column(path="mobile", type="string")
     */
    private string $mobile = '';

    /**
     * @JUSTIMMO\Column(path="phone", type="string")
     */
    private string $phone = '';

    /**
     * @JUSTIMMO\Column(path="fax", type="string")
     */
    private string $fax = '';

    /**
     * @JUSTIMMO\Column(path="email", type="string")
     */
    private string $email;

    /**
     * @JUSTIMMO\Column(path="companyName", type="string")
     */
    private string $companyName = '';

    /**
     * @JUSTIMMO\Relation(path="website", targetEntity="Justimmo\Api\Entity\ParsedUrl")
     */
    private ParsedUrl $website;

    /**
     * @JUSTIMMO\Relation(path="address", targetEntity="Justimmo\Api\Entity\Geo\Address")
     */
    private Address $address;

    /**
     * @JUSTIMMO\Relation(path="profilePicture", targetEntity="Justimmo\Api\Entity\Attachment\Attachment")
     */
    private Attachment $profilePicture;

    /**
     * @JUSTIMMO\Relation(path="pictures", targetEntity="Justimmo\Api\Entity\Attachment\Attachment", multiple=true)
     */
    private ResultSet $pictures;

    /**
     * @JUSTIMMO\Relation(path="employeeCategories", targetEntity="Justimmo\Api\Entity\Employee\Category", multiple=true)
     */
    private ResultSet $categories;

    /**
     * @JUSTIMMO\Relation(path="links", targetEntity="Justimmo\Api\Entity\Attachment\Link", multiple=true)
     */
    private ResultSet $links;

    /**
     * @JUSTIMMO\Relation(path="immobilienCard", targetEntity="Justimmo\Api\Entity\Employee\ImmobilienCard")
     */
    private ?ImmobilienCard $immobilienCard;

    /**
     * @var int[]
     * @JUSTIMMO\Column(path="realtyIds", type="integer", multiple=true)
     */
    private array $realtyIds;

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getWebsite(): ParsedUrl
    {
        return $this->website;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getProfilePicture(): Attachment
    {
        return $this->profilePicture;
    }

    public function getPictures(): ResultSet
    {
        return $this->pictures;
    }

    public function getCategories(): ResultSet
    {
        return $this->categories;
    }

    public function getLinks(): ResultSet
    {
        return $this->links;
    }

    /**
     * @return int[]
     */
    public function getRealtyIds(): array
    {
        return $this->realtyIds;
    }

    public function getImmobilienCard(): ?ImmobilienCard
    {
        return $this->immobilienCard;
    }
}
