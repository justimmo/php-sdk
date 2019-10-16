<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class City implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column(path="city", type="string")
     */
    private $city;


    public function __toString()
    {
        return (string) $this->getCity();
    }


    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
