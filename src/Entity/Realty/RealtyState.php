<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class RealtyState implements Entity
{
    use Identifiable, Nameable;

    const STATUS_DRAFT                = 4;
    const STATUS_ACTIVE               = 5;
    const STATUS_INACTIVE             = 6;
    const STATUS_RESERVED             = 7;
    const STATUS_CONTRACT_PREPARATION = 11;
    const STATUS_PROCURED             = 8;
    const STATUS_CANCELED             = 9;
    const STATUS_EXTERNAL_PROCURED    = 10;

    const STATUS_IN_ACTIVE_MARKETING = [
        self::STATUS_ACTIVE,
        self::STATUS_RESERVED,
        self::STATUS_CONTRACT_PREPARATION
    ];

    public function __toString()
    {
        return (string) $this->getName();
    }
}
