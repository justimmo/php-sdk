<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity
 */
class Occupancy implements Entity
{
    const LIVING     = 'living';
    const COMMERCIAL = 'commercial';
    const INVESTMENT = 'investment';

    /**
     * @var array
     * @JUSTIMMO\Column(type="original")
     */
    private $occupancies;

    /**
     * @return bool
     */
    public function isLiving()
    {
        return !empty($this->occupancies[self::LIVING]);
    }

    /**
     * @return bool
     */
    public function isCommercial()
    {
        return !empty($this->occupancies[self::COMMERCIAL]);
    }

    /**
     * @return bool
     */
    public function isInvestment()
    {
        return !empty($this->occupancies[self::INVESTMENT]);
    }
}
