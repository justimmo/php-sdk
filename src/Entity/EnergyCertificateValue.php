<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class EnergyCertificateValue implements Entity
{
    /**
     * @var float
     * @JUSTIMMO\Column(type="float")
     */
    private $value;

    /**
     * @var string
     * @JUSTIMMO\Column
     */
    private $class;

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
