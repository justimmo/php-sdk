<?php

namespace Justimmo\Request;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Mapper\MapperInterface;

class RealtyInquiryRequest implements RequestInterface
{
    /** @var JustimmoApiInterface */
    protected $api;

    /** @var MapperInterface */
    protected $mapper;

    protected $realtyId;

    protected $firstName = null;

    protected $lastName = null;

    protected $email = null;

    protected $phone = null;

    protected $message = null;

    protected $street = null;

    protected $zipCode = null;

    protected $city = null;

    protected $country = null;

    /** @var string */
    protected $title = null;

    /** @var int */
    protected $salutationId = null;

    /** @var string */
    protected $category = null;

    /**
     * @var null|int[]
     */
    protected $newsletter = null;

    /**
     * @var bool
     */
    protected $doubleoptinCompleted = false;

    /**
     * @var bool
     */
    protected $isRealtyOwner = false;

    /**
     * @var bool
     */
    protected $realtyOwnerConsultationRequest = false;

    /**
     * @var string
     */
    protected $company = null;

    /**
     * @var bool
     */
    protected $fundingInquiry = false;

    public function __construct(JustimmoApiInterface $api, MapperInterface $mapper)
    {
        $this->api    = $api;
        $this->mapper = $mapper;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setEmail($value)
    {
        $this->email = $value;

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
     * @param null $value
     *
     * @return $this
     */
    public function setFirstName($value)
    {
        $this->firstName = $value;

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
     * @param null $value
     *
     * @return $this
     */
    public function setLastName($value)
    {
        $this->lastName = $value;

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
     * @param null $value
     *
     * @return $this
     */
    public function setMessage($value)
    {
        $this->message = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setPhone($value)
    {
        $this->phone = $value;

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
     * @param mixed $value
     *
     * @return $this
     */
    public function setRealtyId($value)
    {
        $this->realtyId = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRealtyId()
    {
        return $this->realtyId;
    }

    /**
     * @return null
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setZipCode($value)
    {
        $this->zipCode = $value;

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
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setCountry($value)
    {
        $this->country = $value;

        return $this;
    }

    /**
     * @return null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null $value
     *
     * @return $this
     */
    public function setCity($value)
    {
        $this->city = $value;

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
     * @return int
     */
    public function getSalutationId()
    {
        return $this->salutationId;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param int $salutationId
     */
    public function setSalutationId($salutationId)
    {
        $this->salutationId = $salutationId;

        return $this;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @param bool $isRealtyOwner
     */
    public function setIsRealtyOwner($isRealtyOwner)
    {
        $this->isRealtyOwner = $isRealtyOwner;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRealtyOwner()
    {
        return $this->isRealtyOwner;
    }

    /**
     * @param bool $realtyOwnerConsultationRequest
     */
    public function setRealtyOwnerConsultationRequest($realtyOwnerConsultationRequest)
    {
        $this->realtyOwnerConsultationRequest = $realtyOwnerConsultationRequest;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRealtyOwnerConsultationRequest()
    {
        return $this->realtyOwnerConsultationRequest;
    }

    /**
     * @return bool
     */
    public function isFundingInquiry()
    {
        return $this->fundingInquiry;
    }

    /**
     * @param bool $fundingInquiry
     */
    public function setFundingInquiry($fundingInquiry)
    {
        $this->fundingInquiry = $fundingInquiry;

        return $this;
    }

    /**
     * @param int[] $newsletter           An array of newsletter category ids of justimmo
     * @param bool  $doubleoptinCompleted Wether a double optin has a already been completed.
     *                                    If false, JUSTIMMO will handle double optin and send an email to the contact automatically
     */
    public function registerForNewsletter(array $newsletter, bool $doubleoptinCompleted)
    {
        $this->newsletter = $newsletter;
    }

    public function send()
    {
        $this->api->postRealtyInquiry(array(
            $this->mapper->getFilterPropertyName('realtyId')     => $this->getRealtyId(),
            $this->mapper->getFilterPropertyName('firstName')    => $this->getFirstName(),
            $this->mapper->getFilterPropertyName('lastName')     => $this->getLastName(),
            $this->mapper->getFilterPropertyName('email')        => $this->getEmail(),
            $this->mapper->getFilterPropertyName('phone')        => $this->getPhone(),
            $this->mapper->getFilterPropertyName('message')      => $this->getMessage(),
            $this->mapper->getFilterPropertyName('street')       => $this->getStreet(),
            $this->mapper->getFilterPropertyName('zipCode')      => $this->getZipCode(),
            $this->mapper->getFilterPropertyName('city')         => $this->getCity(),
            $this->mapper->getFilterPropertyName('country')      => $this->getCountry(),
            $this->mapper->getFilterPropertyName('title')        => $this->getTitle(),
            $this->mapper->getFilterPropertyName('salutationId') => $this->getSalutationId(),
            $this->mapper->getFilterPropertyName('category')     => $this->getCategory(),
            $this->mapper->getFilterPropertyName('company')      => $this->getCompany(),
            'newsletter'                                         => $this->newsletter,
            'doubleoptin_completed'                              => $this->doubleoptinCompleted ? '1' : '0',
            'is_realty_owner'                                    => $this->isRealtyOwner ? '1' : '0',
            'realty_owner_consultation_request'                  => $this->realtyOwnerConsultationRequest ? '1' : '0',
            $this->mapper->getFilterPropertyName('fundingInquiry') => $this->isFundingInquiry() ? '1' : '0',
        ));
    }
}
