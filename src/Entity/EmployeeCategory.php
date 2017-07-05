<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class EmployeeCategory extends BaseEntity
{
    /**
     * @var Employee[]|\Justimmo\Api\ResultSet\ResultSet
     * @JUSTIMMO\Relation(path=employees, targetEntity="Justimmo\Api\Entity\Employee", multiple=true)
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
