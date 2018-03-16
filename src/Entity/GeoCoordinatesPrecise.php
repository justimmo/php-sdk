<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class GeoCoordinatesPrecise implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(path="lat", type="float")
     */
    private $latitudePrecise;

    /**
     * @var float
     * @JUSTIMMO\Column(path="lng", type="float")
     */
    private $longitudePrecise;

    /**
     * @return float
     */
    public function getLatitudePrecise()
    {
        return $this->latitudePrecise;
    }

    /**
     * @return float
     */
    public function getLongitudePrecise()
    {
        return $this->longitudePrecise;
    }
}
