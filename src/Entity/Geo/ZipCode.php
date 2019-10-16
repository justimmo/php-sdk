<?php

namespace Justimmo\Api\Entity\Geo;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class ZipCode implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column(path="zip", type="string")
     */
    private $zip;


    public function __toString()
    {
        return (string) $this->getZip();
    }


    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }
}
