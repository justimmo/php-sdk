<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Geo\Address;

/**
 * @JUSTIMMO\Entity()
 * @JUSTIMMO\PreHydrate(moveTo={
 *     "coordinates"="address",
 * })
 */
class Tenant implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $agency;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $email;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $phone;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $uid;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $registerNumber;

    /**
     * @var Address
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Geo\Address")
     */
    private $address;


    public function __toString()
    {
        return (string) $this->getAgency();
    }

    /**
     * @return string
     */
    public function getAgency()
    {
        return $this->agency;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getRegisterNumber()
    {
        return $this->registerNumber;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
