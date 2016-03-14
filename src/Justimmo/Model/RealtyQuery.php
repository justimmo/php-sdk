<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

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
 * @method RealtyQuery filterByKeyword($value)
 * @method RealtyQuery filterByFederalStateId($value)
 * @method RealtyQuery filterByStatusId($value)
 * @method RealtyQuery filterByRent($value)
 * @method RealtyQuery filterByBuy($value)
 * @method RealtyQuery orderByPrice($direction = 'asc')
 * @method RealtyQuery orderByPropertyNumber($direction = 'asc')
 * @method RealtyQuery orderByArea($direction = 'asc')
 * @method RealtyQuery orderByLivingArea($direction = 'asc')
 * @method RealtyQuery orderByFloorArea($direction = 'asc')
 * @method RealtyQuery orderBySurfaceArea($direction = 'asc')
 * @method RealtyQuery orderByCreatedAt($direction = 'asc')
 * @method RealtyQuery orderByUpdatedAt($direction = 'asc')
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

    /**
     * get a array of realty ids
     *
     * @return array
     */
    public function findIds()
    {
        $response = $this->api->callRealtyIds($this->params);

        return json_decode($response);
    }
}
