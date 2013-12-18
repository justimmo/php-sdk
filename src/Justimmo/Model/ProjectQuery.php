<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

class ProjectQuery extends AbstractQuery
{

    public function getListCall()
    {
        return 'callProjectList';
    }

    public function getDetailCall()
    {
        return 'callProjectDetail';
    }

}
