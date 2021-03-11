<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class EmployeeQuery
 * @package Justimmo\Model
 *
 * @method Employee findPk($pk)
 * @method Employee|null findOne($pk)
 */
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
