<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * @JUSTIMMO\Entity
 */
class AddressDisplayRule implements Entity
{
    const NOTHING = 'nothing';
    const CITY    = 'city';
    const FUZZY   = 'fuzzy';
    const STREET  = 'street';
    const FULL    = 'full';

    /**
     * @var string
     *
     * @JUSTIMMO\Column
     */

    /**
     * @var string
     *
     * @JUSTIMMO\Column(path="rule", type="string")
     */
    private $rule;

    /**
     * @var boolean
     *
     * @JUSTIMMO\Column(path="exportStairTop", type="boolean")
     */
    private $exportStairTop;

    /**
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return boolean
     */
    public function getExportStairTop()
    {
        return $this->exportStairTop;
    }

    /**
     * Returns true if the address should be displayed with all its details
     *
     * @return bool
     */
    public function isFull()
    {
        return $this->rule === self::FULL;
    }

    /**
     * Returns true if the address should be completely hidden
     *
     * @return bool
     */
    public function isNothing()
    {
        return $this->rule === self::NOTHING;
    }

    /**
     * Returns true if only city, zip-code and street (without number) should be displayed
     *
     * @return bool
     */
    public function isStreet()
    {
        return $this->rule === self::STREET;
    }

    /**
     * Returns true if only city and zip-code should be displayed
     *
     * @return bool
     */
    public function isCity()
    {
        return $this->rule === self::CITY;
    }

    /**
     * Returns true if fuzzy coordinates should be displayed
     *
     * @return bool
     */
    public function isFuzzy()
    {
        return $this->rule === self::FUZZY;
    }
}