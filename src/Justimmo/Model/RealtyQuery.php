<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class RealtyQuery
 * @package Justimmo\Model
 *
 * @method Realty findPk($pk)
 * @method Realty|null findOne($pk)
 * @method RealtyQuery filterByPrice($value)
 * @method RealtyQuery filterByRealtyTypeId($value)
 * @method RealtyQuery filterBySubRealtyTypeId($value)
 * @method RealtyQuery filterByStyleOfBuildingId($value)
 * @method RealtyQuery filterByZipCode($value)
 * @method RealtyQuery filterByRooms($value)
 * @method RealtyQuery filterByPropertyNumber($value)
 * @method RealtyQuery filterByArea($value)
 * @method RealtyQuery filterByLivingArea($value)
 * @method RealtyQuery filterByFloorArea($value)
 * @method RealtyQuery filterBySurfaceArea($value)
 * @method RealtyQuery filterByGardenCount($value)
 * @method RealtyQuery filterByBalconyCount($value)
 * @method RealtyQuery filterByLoggiaCount($value)
 * @method RealtyQuery filterByTerraceCount($value)
 * @method RealtyQuery filterByCellarCount($value)
 * @method RealtyQuery filterByGarageCount($value)
 * @method RealtyQuery filterByParkingCount($value)
 * @method RealtyQuery filterByToiletRoomCount($value)
 * @method RealtyQuery filterByBathRoomCount($value)
 * @method RealtyQuery filterByStoreRoomCount($value)
 * @method RealtyQuery filterByDisabilityAccess($value)
 * @method RealtyQuery filterByCondition($value)
 * @method RealtyQuery filterByKeyword($value)
 * @method RealtyQuery filterByFederalStateId($value)
 * @method RealtyQuery filterByStatusId($value)
 * @method RealtyQuery filterByRent($value)
 * @method RealtyQuery filterByBuy($value)
 * @method RealtyQuery filterByRealtySystemType($value)
 * @method RealtyQuery filterByParentId($value)
 * @method RealtyQuery filterByRentPerSqm($value)
 * @method RealtyQuery filterByUpdatedAt($value)
 * @method RealtyQuery filterByRentWithPurchaseOption($value)
 * @method RealtyQuery filterByPoliticalDistrictId($value)
 * @method RealtyQuery filterByCountryId($value)
 * @method RealtyQuery filterByOwnerId($value)
 * @method RealtyQuery orderByPrice($direction = 'asc')
 * @method RealtyQuery orderByPropertyNumber($direction = 'asc')
 * @method RealtyQuery orderByArea($direction = 'asc')
 * @method RealtyQuery orderByLivingArea($direction = 'asc')
 * @method RealtyQuery orderByFloorArea($direction = 'asc')
 * @method RealtyQuery orderBySurfaceArea($direction = 'asc')
 * @method RealtyQuery orderByCreatedAt($direction = 'asc')
 * @method RealtyQuery orderByPublishedAt($direction = 'asc')
 * @method RealtyQuery orderByRooms($direction = 'asc')
 * @method RealtyQuery orderByZipCode($direction = 'asc')
 */
class RealtyQuery extends AbstractQuery
{
    /**
     * @inheritdoc
     */
    public function getListCall()
    {
        return 'callRealtyList';
    }

    /**
     * @inheritdoc
     */
    public function getDetailCall()
    {
        return 'callRealtyDetail';
    }

    /**
     * @inheritdoc
     */
    public function getIdsCall()
    {
        return 'callRealtyIds';
    }

    /**
     * The api sorts by updated_at alone, whatever the mapper calls the column
     *
     * RealtyMapper::getOrderMapping() names it too, but a mapper from outside the SDK may only know
     * its filter names, and the filter is aktualisiert_am. So the column is named here.
     *
     * @param string $direction
     *
     * @return $this
     */
    public function orderByUpdatedAt($direction = 'asc')
    {
        return $this->order('updated_at', $direction);
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
     * Search commercial realties by usable area of the sub realties
     *
     * @param bool $all
     *
     * @return $this
     */
    public function preciseAreaSearch($all = true)
    {
        return $this->set('preciseAreaSearch', (int) $all);
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
     * Filters by the date a realty was created.
     *
     * The api has no filter[created_at] and never had one, so this has always been discarded and
     * the search came back unfiltered. Strict mode rejects it.
     *
     * @deprecated The api has no such filter. There is no replacement; filter by
     *             filterByUpdatedAt() if a date range is what you need. Removed in 2.x
     *
     * @param string|array<string, mixed> $value
     *
     * @return $this
     */
    public function filterByCreatedAt($value)
    {
        return $this->filterThroughMapper('CreatedAt', $value, 'filterByCreatedAt');
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
        return $this->appendFilter($this->mapper->getFilterPropertyName('RealtyCategory'), $value);
    }

    /**
     * The api requires a list here and rejects a scalar with a 422, so a single value is wrapped
     *
     * @param string|int|array<int, string|int> $value
     *
     * @return $this
     */
    public function filterByEquipment($value)
    {
        return $this->filter(
            $this->mapper->getFilterPropertyName('Equipment'),
            is_array($value) ? $value : [$value]
        );
    }
}
