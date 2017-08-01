<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;

/**
 * @JUSTIMMO\Entity
 */
class Inquiry implements Entity
{
    use Identifiable;

    /**
     * @var string
     * @JUSTIMMO\Column(type="string")
     */
    private $contactId;

    /**
     * @return string
     */
    public function getContactId()
    {
        return $this->contactId;
    }
}
