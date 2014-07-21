<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class RealtyQuery
 * @package Justimmo\Model
 *
 * @method RealtyQuery filterByPrice($value)
 * @method RealtyQuery filterByRealtyTypeId($value)
 * @method RealtyQuery filterByRealtyCategory($value)
 * @method RealtyQuery filterByTag($value)
 * @method RealtyQuery filterByZipCode($value)
 * @method RealtyQuery filterByRooms($value)
 * @method RealtyQuery filterByPropertyNumber($value)
 * @method RealtyQuery filterByLivingArea($value)
 * @method RealtyQuery filterByFloorArea($value)
 * @method RealtyQuery filterBySurfaceArea($value)
 * @method RealtyQuery filterByKeyword($value)
 * @method RealtyQuery filterByFederalStateId($value)
 * @method RealtyQuery filterByRent($value)
 * @method RealtyQuery filterByBuy($value)
 */
class RealtyQuery extends AbstractQuery
{
    /**
     * returns the method name what should be called on the api class for a list call
     *
     * @return string
     */
    public function getListCall()
    {
        return 'callRealtyList';
    }

    /**
     * returns the method name what should be called on the api class for a detail call
     *
     * @return string
     */
    public function getDetailCall()
    {
        return 'callRealtyDetail';
    }
}
