<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;
use Justimmo\Api\Entity\Traits\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class EmployeeCategory implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @var Employee[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path="employees", targetEntity="Justimmo\Api\Entity\Employee", multiple=true)
     */
    private $employees;

    /**
     * @return Employee[]
     */
    public function getEmployees()
    {
        return $this->employees;
    }
}
