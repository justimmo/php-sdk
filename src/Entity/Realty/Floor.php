<?php

namespace Justimmo\Api\Entity\Realty;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;

/**
 * Class Floor
 * @package Justimmo\Api\Entity\Realty
 *
 * @JUSTIMMO\Entity()
 */
class Floor implements Entity
{
    /**
     * @var int
     * @JUSTIMMO\Column(path="floor", type="integer")
     */
    private $floor;

    /**
     * @var bool
     * @JUSTIMMO\Column(path="top", type="boolean")
     */
    private $top;

    /**
     * @var bool
     * @JUSTIMMO\Column(path="basement", type="boolean")
     */
    private $basement;

    /**
     * @var string
     * @JUSTIMMO\Column(path="description", type="string")
     */
    private $description;


    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }


    /**
     * @return bool
     */
    public function isTop()
    {
        return $this->top;
    }


    /**
     * @return bool
     */
    public function isBasement()
    {
        return $this->basement;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}
