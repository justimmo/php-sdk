<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
* @JUSTIMMO\Entity()
*/
class GeoCoordinates implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(path="lat", type="float")
     */
    private $latitude;

    /**
     * @var float
     * @JUSTIMMO\Column(path="lng", type="float")
     */
    private $longitude;

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
