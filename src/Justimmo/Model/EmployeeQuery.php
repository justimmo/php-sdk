<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

class EmployeeQuery extends AbstractQuery
{

    public function getListCall()
    {
        return 'callEmployeeList';
    }

    public function getDetailCall()
    {
        return 'callEmployeeDetail';
    }

}
