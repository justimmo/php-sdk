<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity(cacheKey="state")
 */
class MarketingState implements Entity
{
    const ACTIVE = 'active';
    const TEASER = 'teaser';

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
     * @return bool
     */
    public function isActive()
    {
        return $this->state === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isTeaser()
    {
        return $this->state === self::TEASER;
    }
}
