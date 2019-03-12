<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity()
 */
class PoiGroups implements Entity
{
    /**
     * @var PoiType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="children_and_school", targetEntity="Justimmo\Api\Entity\Realty\PoiType", multiple=true)
     */
    private $childrenAndSchool;

    /**
     * @var PoiType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="health", targetEntity="Justimmo\Api\Entity\Realty\PoiType", multiple=true)
     */
    private $health;

    /**
     * @var PoiType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="local_supply", targetEntity="Justimmo\Api\Entity\Realty\PoiType", multiple=true)
     */
    private $localSupply;

    /**
     * @var PoiType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="other", targetEntity="Justimmo\Api\Entity\Realty\PoiType", multiple=true)
     */
    private $other;

    /**
     * @var PoiType[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="traffic", targetEntity="Justimmo\Api\Entity\Realty\PoiType", multiple=true)
     */
    private $traffic;


    public function getChildrenAndSchool()
    {
        return $this->childrenAndSchool;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function getLocalSupply()
    {
        return $this->localSupply;
    }

    public function getOther()
    {
        return $this->other;
    }

    public function getTraffic()
    {
        return $this->traffic;
    }

}
