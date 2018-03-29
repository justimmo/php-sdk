<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity(cacheKey="state")
 */
class BuildingProgress implements Entity
{
    const BUILDING = 'building';
    const PLANNING = 'planning';
    const FINISHED = 'finished';

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $state;

    public function __toString()
    {
        return (string) $this->state;
    }

    /**
     * Returns true if the realty is still in planning (Only residential projects)
     *
     * @return bool
     */
    public function isPlanning()
    {
        return $this->state === self::PLANNING;
    }

    /**
     * Returns true if the realty is still in building (Only residential projects)
     *
     * @return bool
     */
    public function isBuilding()
    {
        return $this->state === self::BUILDING;
    }

    /**
     * Returns true if the realty state is finished (Only residential projects)
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->state === self::FINISHED;
    }
}
