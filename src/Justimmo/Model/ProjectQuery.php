<?php

namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class ProjectQuery
 * @package Justimmo\Model
 *
 * @method Project findPk($pk)
 * @method Project|null findOne($pk)
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
     * Filters by a keyword, filter[stichwort]
     *
     * @param string $value
     *
     * @return $this
     */
    public function filterByKeyword($value)
    {
        return $this->filterThroughMapper('Keyword', $value, 'filterByKeyword');
    }

    /**
     * Filters by the federal state, filter[bundesland_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByFederalStateId($value)
    {
        return $this->filterThroughMapper('FederalStateId', $value, 'filterByFederalStateId');
    }

    /**
     * Filters by the state of the project, filter[projekt_status]
     *
     * @param string|array<int, string> $value planning, building or finished, or a list of them
     *
     * @return $this
     */
    public function filterByProjectState($value)
    {
        return $this->filterThroughMapper('ProjectState', $value, 'filterByProjectState');
    }

    /**
     * Filters by whether the project is a reference, filter[referenz]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByIsReference($value)
    {
        return $this->filterThroughMapper('IsReference', $value, 'filterByIsReference');
    }

    /**
     * Filters by the completion date, filter[fertigstellung_von] and filter[fertigstellung_bis]
     *
     * @param array{min?: string, max?: string} $value dates, for example 2026-01-31
     *
     * @return $this
     */
    public function filterByCompletionDate($value)
    {
        return $this->filterThroughMapper('CompletionDate', $value, 'filterByCompletionDate');
    }

    /**
     * Filters by the start of the sale, filter[verkaufsstart_von] and filter[verkaufsstart_bis]
     *
     * @param array{min?: string, max?: string} $value dates, for example 2026-01-31
     *
     * @return $this
     */
    public function filterBySaleStart($value)
    {
        return $this->filterThroughMapper('SaleStart', $value, 'filterBySaleStart');
    }

    /**
     * Filters by the tag of the project, filter[project_tag_name]
     *
     * @param string|array<int, string> $value one tag or a list of tags
     *
     * @return $this
     */
    public function filterByProjectTag($value)
    {
        return $this->filterThroughMapper('ProjectTag', $value, 'filterByProjectTag');
    }

    /**
     * Filters by whether a realty of the project is for sale, filter[kauf]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByBuy($value)
    {
        return $this->filterThroughMapper('Buy', $value, 'filterByBuy');
    }

    /**
     * Filters by whether a realty of the project is for rent, filter[miete]
     *
     * @param int|bool $value 1 or true for yes, 0 or false for no
     *
     * @return $this
     */
    public function filterByRent($value)
    {
        return $this->filterThroughMapper('Rent', $value, 'filterByRent');
    }

    /**
     * Filters by the realty type, filter[objektart_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByRealtyTypeId($value)
    {
        return $this->filterThroughMapper('RealtyTypeId', $value, 'filterByRealtyTypeId');
    }

    /**
     * Filters by the sub realty type, filter[subobjektart_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterBySubRealtyTypeId($value)
    {
        return $this->filterThroughMapper('SubRealtyTypeId', $value, 'filterBySubRealtyTypeId');
    }

    /**
     * Filters by the status of the realties, filter[objekt_status_id]
     *
     * @param int|array<int, int> $value one id or a list of ids
     *
     * @return $this
     */
    public function filterByStatusId($value)
    {
        return $this->filterThroughMapper('StatusId', $value, 'filterByStatusId');
    }

    /**
     * Filters by the zip code, filter[plz], or filter[plz_von] and filter[plz_bis]
     *
     * @param string|array<int, string>|array{min?: string, max?: string} $value one zip code, a list of them, or a range
     *
     * @return $this
     */
    public function filterByZipCode($value)
    {
        return $this->filterThroughMapper('ZipCode', $value, 'filterByZipCode');
    }

    /**
     * Filters by the property number of a realty, filter[objektnummer], or filter[objektnummer_von] and filter[objektnummer_bis]
     *
     * @param string|array{min?: string, max?: string} $value one property number, or a range
     *
     * @return $this
     */
    public function filterByPropertyNumber($value)
    {
        return $this->filterThroughMapper('PropertyNumber', $value, 'filterByPropertyNumber');
    }

    /**
     * Filters by the property number of the project, filter[projekt_objektnummer]
     *
     * @param string $value
     *
     * @return $this
     */
    public function filterByProjectPropertyNumber($value)
    {
        return $this->filterThroughMapper('ProjectPropertyNumber', $value, 'filterByProjectPropertyNumber');
    }

    /**
     * Filters by the owner, filter[owner_id]
     *
     * @param int $value the id of the owner
     *
     * @return $this
     */
    public function filterByOwnerId($value)
    {
        return $this->filterThroughMapper('OwnerId', $value, 'filterByOwnerId');
    }

    /**
     * Filters by the price, filter[preis_von] and filter[preis_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByPrice($value)
    {
        return $this->filterThroughMapper('Price', $value, 'filterByPrice');
    }

    /**
     * Filters by the number of rooms, filter[zimmer_von] and filter[zimmer_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByRooms($value)
    {
        return $this->filterThroughMapper('Rooms', $value, 'filterByRooms');
    }

    /**
     * Filters by the area, filter[flaeche_von] and filter[flaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByArea($value)
    {
        return $this->filterThroughMapper('Area', $value, 'filterByArea');
    }

    /**
     * Filters by the living area, filter[wohnflaeche_von] and filter[wohnflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByLivingArea($value)
    {
        return $this->filterThroughMapper('LivingArea', $value, 'filterByLivingArea');
    }

    /**
     * Filters by the floor area, filter[nutzflaeche_von] and filter[nutzflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterByFloorArea($value)
    {
        return $this->filterThroughMapper('FloorArea', $value, 'filterByFloorArea');
    }

    /**
     * Filters by the surface area, filter[grundflaeche_von] and filter[grundflaeche_bis]
     *
     * @param array{min?: int|float, max?: int|float} $value
     *
     * @return $this
     */
    public function filterBySurfaceArea($value)
    {
        return $this->filterThroughMapper('SurfaceArea', $value, 'filterBySurfaceArea');
    }

    /**
     * Sorts by the date the project was created, orderby=created_at
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByCreatedAt($direction = 'asc')
    {
        return $this->orderThroughMapper('CreatedAt', $direction);
    }

    /**
     * Sorts by the date of the last update, orderby=updated_at
     *
     * @param string $direction asc or desc
     *
     * @return $this
     */
    public function orderByUpdatedAt($direction = 'asc')
    {
        return $this->orderThroughMapper('UpdatedAt', $direction);
    }

    /**
     * {@inheritdoc}
     *
     * projekt/detail is the only detail call which documents alleProjektObjekte and objektIds, so
     * it is the only one which still receives them
     */
    protected function getDetailParams(): array
    {
        return ['picturesize', 'culture', 'alleProjektObjekte', 'objektIds'];
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
        return $this->setParameter('alle', (int) $all);
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
        return $this->setParameter('alleProjektObjekte', (int) $all);
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
        return $this->setParameter('objektIds', (int) $value);
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
        $this->triggerDeprecation('ProjectQuery::filterByCountryIso2() is deprecated and is removed in 2.0.0. The api has no land_iso2 filter, so it has never filtered.');

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
     * filter of its own, so this name says something the api does not have.
     *
     * @deprecated Use filterByTag(). Both build filter[tag_name]; this name suggests a filter
     *             which does not exist. Removed in 2.x
     *
     * @param string|array<int, string> $value
     *
     * @return $this
     */
    public function filterByRealtyCategory($value)
    {
        $this->triggerDeprecation('filterByRealtyCategory() is deprecated and is removed in 2.0.0. Use filterByTag(), which builds the same filter.');

        return $this->appendFilter($this->mapper->getFilterPropertyName('RealtyCategory'), $value);
    }
}
