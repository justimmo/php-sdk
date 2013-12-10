<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

/**
 * Class ObjektQuery
 * @package Justimmo\Model
 *
 * @method ObjektQuery filterByPreis($value)
 * @method ObjektQuery filterByObjektartId($value)
 * @method ObjektQuery filterByObjektkategorie($value)
 * @method ObjektQuery filterByPlz($value)
 * @method ObjektQuery filterByZimmer($value)
 * @method ObjektQuery filterByObjektnummer($value)
 * @method ObjektQuery filterByWohnflaeche($value)
 * @method ObjektQuery filterByNutzflaeche($value)
 * @method ObjektQuery filterByGrundflaeche($value)
 * @method ObjektQuery filterByStichwort($value)
 * @method ObjektQuery filterByBundeslandId($value)
 */
class ObjektQuery extends AbstractQuery
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
        return 'callObjektList';
    }

    /**
     * returns the method name what should be called on the api class for a detail call
     *
     * @return string
     */
    public function getDetailCall()
    {
        return 'callObjektDetail';
    }
}
