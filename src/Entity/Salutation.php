<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Salutation implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }
}
