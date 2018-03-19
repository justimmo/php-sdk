<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Feature implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @var FeatureGroup
     * @JUSTIMMO\Relation(path="featureGroup", targetEntity="Justimmo\Api\Entity\Realty\FeatureGroup")
     */
    private $featureGroup;

    /**
     * @return FeatureGroup
     */
    public function getFeatureGroup()
    {
        return $this->featureGroup;
    }
}
