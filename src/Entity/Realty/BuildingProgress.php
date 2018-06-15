<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\DateFormatable;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class BuildingProgress implements Entity
{
    use Identifiable, Nameable, DateFormatable;

    const BUILDING = 'building';
    const PLANNING = 'planning';
    const FINISHED = 'finished';

    /**
     * @var \DateTime
     * @JUSTIMMO\Column(type="date")
     */
    private $completionDate;

    /**
     * @param string $format Date format to be returned. If left empty the \DateTime class is returned.
     *
     * @return \DateTime|string
     */
    public function getCompletionDate($format = null)
    {
        return $this->formatDate($this->completionDate, $format);
    }

    /**
     * Returns true if the realty is still in planning (Only residential projects)
     *
     * @return bool
     */
    public function isPlanning()
    {
        return $this->id === self::PLANNING;
    }

    /**
     * Returns true if the realty is still in building (Only residential projects)
     *
     * @return bool
     */
    public function isBuilding()
    {
        return $this->id === self::BUILDING;
    }

    /**
     * Returns true if the realty state is finished (Only residential projects)
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->id === self::FINISHED;
    }
}
