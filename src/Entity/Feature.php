<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Feature extends BaseEntity
{
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
