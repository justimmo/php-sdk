<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Tests\Model\ProjectQueryTest;

/**
 * Class RealtyQuery
 * @package Justimmo\Model
 *
 * @method RealtyQuery filterByPrice($value)
 * @method RealtyQuery filterByRealtyTypeId($value)
 * @method RealtyQuery filterBySubRealtyTypeId($value)
 * @method RealtyQuery filterByStyleOfBuildingId($value)
 * @method RealtyQuery filterByRealtyCategory($value)
 * @method RealtyQuery filterByTag($value)
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
 * @method RealtyQuery filterByEquipment($value)
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
 * @method RealtyQuery orderByPrice($direction = 'asc')
 * @method RealtyQuery orderByPropertyNumber($direction = 'asc')
 * @method RealtyQuery orderByArea($direction = 'asc')
 * @method RealtyQuery orderByLivingArea($direction = 'asc')
 * @method RealtyQuery orderByFloorArea($direction = 'asc')
 * @method RealtyQuery orderBySurfaceArea($direction = 'asc')
 * @method RealtyQuery orderByCreatedAt($direction = 'asc')
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
     * Api only accepts english updated_at fieldname for ordering
     *
     * @param string $direction
     *
     * @return RealtyQuery
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
}
