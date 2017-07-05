<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity()
 */
class Feature implements Entity
{
    use Identifiable, Nameable;

    /**
     * @var FeatureGroup
     * @JUSTIMMO\Relation(path="featureGroup", targetEntity="Justimmo\Api\Entity\FeatureGroup")
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
