<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class RealtyQuery
 * @package Justimmo\Model
 *
 * @method RealtyQuery filterByPreis($value)
 * @method RealtyQuery filterByObjektartId($value)
 * @method RealtyQuery filterByObjektkategorie($value)
 * @method RealtyQuery filterByPlz($value)
 * @method RealtyQuery filterByZimmer($value)
 * @method RealtyQuery filterByObjektnummer($value)
 * @method RealtyQuery filterByWohnflaeche($value)
 * @method RealtyQuery filterByNutzflaeche($value)
 * @method RealtyQuery filterByGrundflaeche($value)
 * @method RealtyQuery filterByStichwort($value)
 * @method RealtyQuery filterByBundeslandId($value)
 */
class RealtyQuery extends AbstractQuery
{
    protected $filterMapping = array(
        'preis'           => 'preis',
        'objektartid'     => 'objektart_id',
        'objektkategorie' => 'objektkategorie',
        'plz'             => 'plz',
        'zimmer'          => 'zimmer',
        'objektnummer'    => 'objektnummer',
        'wohnflaeche'     => 'wohnflaeche',
        'nutzflaeche'     => 'nutzflaeche',
        'grundflaeche'    => 'grundflaeche',
        'stichwort'       => 'stichwort',
        'bundeslandid'    => 'bundesland_id',
    );

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
