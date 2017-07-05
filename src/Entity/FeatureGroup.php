<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class FeatureGroup extends BaseEntity
{
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
