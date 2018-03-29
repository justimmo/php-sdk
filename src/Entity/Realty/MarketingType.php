<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity(cacheKey="type")
 */
class MarketingType implements Entity
{
    const RENT  = 'rent';
    const BUY   = 'buy';
    const LEASE = 'lease';

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $type;

    public function __toString()
    {
        return (string) $this->type;
    }

    /**
     * @return bool
     */
    public function isLeasable()
    {
        return $this->type === self::LEASE;
    }

    /**
     * @return bool
     */
    public function isRentable()
    {
        return $this->type === self::RENT;
    }

    /**
     * @return bool
     */
    public function isBuyable()
    {
        return $this->type === self::BUY;
    }
}
