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
 * @method ProjectQuery filterByProjectState($value)
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

    /**
     * Return all projects, regardless of realties in projects
     *
     * @param bool $all
     *
     * @return $this
     */
    public function all($all = true)
    {
        return $this->set('alle', (int) $all);
    }

    /**
     * Return all project realties regardless of realty state (active, inactive, draft,...)
     *
     * @param bool $all
     *
     * @return $this
     */
    public function allProjectRealties($all = true)
    {
        return $this->set('alleProjektObjekte', (int) $all);
    }
}
