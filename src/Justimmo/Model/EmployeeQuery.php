<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

class EmployeeQuery extends AbstractQuery
{

    /**
     * @inheritdoc
     */
    public function getListCall()
    {
        return 'callEmployeeList';
    }

    /**
     * @inheritdoc
     */
    public function getDetailCall()
    {
        return 'callEmployeeDetail';
    }

    /**
     * @inheritdoc
     */
    public function getIdsCall()
    {
        return 'callEmployeeIds';
    }
}
