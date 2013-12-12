<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

class MitarbeiterQuery extends AbstractQuery
{

    public function getListCall()
    {
        return 'callMitarbeiterList';
    }

    public function getDetailCall()
    {
        // TODO: Implement getDetailCall() method.
    }

}