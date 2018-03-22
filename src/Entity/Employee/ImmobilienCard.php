<?php

namespace Justimmo\Api\Entity\Employee;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;

/**
 * @JUSTIMMO\Entity()
 */
class ImmobilienCard implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column(path="uid", type="string")
     */
    private $uid;

    /**
     * @var boolean
     * @JUSTIMMO\Column(path="valid", type="boolean")
     */
    private $valid;

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }
}
