<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity(cacheKey="type")
 */
class Type implements Entity
{
    /**
     * Describes standalone realties without affiliation to any project
     */
    const SIMPLE = 'simple';

    /**
     * Describes a commercial project with multiple sub areas
     */
    const COMMERCIAL_PROJECT = 'commercial';

    /**
     * Describes an area (possibly splittable) belonging to a commercial project
     */
    const AREA = 'area';

    /**
     * Describes a residential project containing multiple subunits
     */
    const RESIDENTIAL_PROJECT = 'residential';

    /**
     * Describes a subunit of a residential project
     */
    const RESIDENTIAL_SUBUNIT = 'residential_subunit';

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
     * Returns true if the realty is a normal standalone realty
     *
     * @return bool
     */
    public function isSimple()
    {
        return $this->type === self::SIMPLE;
    }

    /**
     * Returns true if the realty is a big commercial project with subrealties
     *
     * @return bool
     */
    public function isCommercialProject()
    {
        return $this->type === self::COMMERCIAL_PROJECT;
    }

    /**
     * Returns true if the realty a partial area belonging to a commercial project
     *
     * @return bool
     */
    public function isArea()
    {
        return $this->type === self::AREA;
    }

    /**
     * Returns true if the realty is a residential project with subrealties
     *
     * @return bool
     */
    public function isResidentialProject()
    {
        return $this->type === self::RESIDENTIAL_PROJECT;
    }

    /**
     * Returns true if the realty is a subunit of a residential project
     *
     * @return bool
     */
    public function isResidentialSubunit()
    {
        return $this->type === self::RESIDENTIAL_SUBUNIT;
    }
}
