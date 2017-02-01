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
 * @method ProjectQuery filterByIsReference($value)
 * @method ProjectQuery filterByCompletionDate($value)
 * @method ProjectQuery filterBySaleStart($value)
 */
class ProjectQuery extends AbstractQuery
{

    /**
     * @inheritdoc
     */
    public function getListCall()
    {
        return 'callProjectList';
    }

    /**
     * @inheritdoc
     */
    public function getDetailCall()
    {
        return 'callProjectDetail';
    }

    /**
     * @inheritdoc
     */
    public function getIdsCall()
    {
        return 'callProjectIds';
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

    /**
     * Return only realty ids instead of realty data in project tags
     *
     * @param bool $value
     *
     * @return ProjectQuery
     */
    public function onlyRealtyIds($value = true)
    {
        return $this->set('objektIds', (int) $value);
    }
}
