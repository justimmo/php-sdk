<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class ProjectQuery
 * @package Justimmo\Model
 *
 * @method Project findPk($pk)
 * @method Project|null findOne($pk)
 * @method ProjectQuery filterByKeyword($value)
 * @method ProjectQuery filterByFederalStateId($value)
 * @method ProjectQuery filterByProjectState($value)
 * @method ProjectQuery filterByIsReference($value)
 * @method ProjectQuery filterByCompletionDate($value)
 * @method ProjectQuery filterBySaleStart($value)
 * @method ProjectQuery filterByProjectTag($value)
 * @method ProjectQuery filterByBuy($value)
 * @method ProjectQuery filterByRent($value)
 * @method ProjectQuery filterByRealtyTypeId($value)
 * @method ProjectQuery filterBySubRealtyTypeId($value)
 * @method ProjectQuery filterByStatusId($value)
 * @method ProjectQuery filterByZipCode($value)
 * @method ProjectQuery filterByPropertyNumber($value)
 * @method ProjectQuery filterByProjectPropertyNumber($value)
 * @method ProjectQuery filterByOwnerId($value)
 * @method ProjectQuery filterByPrice($value)
 * @method ProjectQuery filterByRooms($value)
 * @method ProjectQuery filterByArea($value)
 * @method ProjectQuery filterByLivingArea($value)
 * @method ProjectQuery filterByFloorArea($value)
 * @method ProjectQuery filterBySurfaceArea($value)
 * @method ProjectQuery orderByCreatedAt($direction = 'asc')
 * @method ProjectQuery orderByUpdatedAt($direction = 'asc')
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
     * {@inheritdoc}
     *
     * projekt/detail is the only detail call which documents alleProjektObjekte and objektIds, so
     * it is the only one which still receives them
     */
    protected function getDetailParams(): array
    {
        return ['picturesize', 'alleProjektObjekte', 'objektIds'];
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

    /**
     * @deprecated The api does not support filter[land_iso2], this filter has no effect
     *             and will be removed in 2.x
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function filterByCountryIso2($value)
    {
        return $this;
    }

    /**
     * The api accepts a list here, and filterByRealtyCategory() builds the same wire filter, so
     * both calls add to it instead of the second discarding the first
     *
     * @param string|array<int, string> $value
     *
     * @return $this
     */
    public function filterByTag($value)
    {
        return $this->appendFilter($this->mapper->getFilterPropertyName('Tag'), $value);
    }

    /**
     * An alias of filterByTag(), both build filter[tag_name]. The api has no realty category
     * filter of its own, so this name is deprecated in 1.5 and removed in 2.x
     *
     * @param string|array<int, string> $value
     *
     * @return $this
     */
    public function filterByRealtyCategory($value)
    {
        return $this->appendFilter($this->mapper->getFilterPropertyName('RealtyCategory'), $value);
    }
}
