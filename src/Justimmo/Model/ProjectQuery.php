<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class ProjectQuery
 * @package Justimmo\Model
 *
 * @method ProjectQuery filterByRealtyCategory($value)
 * @method ProjectQuery filterByTag($value)
 * @method ProjectQuery filterByKeyword($value)
 * @method ProjectQuery filterByFederalStateId($value)
 * @method ProjectQuery filterByCountryIso2($value)
 */
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

    public function all($all = 1)
    {
        return $this->set('alle', $all);
    }
}
