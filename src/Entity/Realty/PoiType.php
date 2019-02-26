<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Annotation\Entity;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class PoiType extends Entity
{
    use Nameable;

    /**
     * @var int
     * @JUSTIMMO\Column(path="distance", type="integer")
     */
    private $distance;


    public function getDistance()
    {
        return $this->distance;
    }

}
