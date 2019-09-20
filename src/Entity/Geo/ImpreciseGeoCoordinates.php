<?php

declare(strict_types=1);

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class ImpreciseGeoCoordinates extends GeoCoordinates
{
    /**
     * @var int|null
     * @JUSTIMMO\Column(path="radius", type="integer")
     */
    private $radius;

    public function getRadius() : ?int
    {
        return $this->radius;
    }
}
