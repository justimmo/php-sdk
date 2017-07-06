<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class FeatureGroup implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @var Feature[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="features", targetEntity="Justimmo\Api\Entity\Feature", multiple=true)
     */
    private $features;

    /**
     * @return Feature[]
     */
    public function getFeatures()
    {
        return $this->features;
    }
}
