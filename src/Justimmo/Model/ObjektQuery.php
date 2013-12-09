<?php
namespace Justimmo\Model;

use Justimmo\Model\Query\AbstractQuery;

class ObjektQuery extends AbstractQuery
{
    protected $filterMapping = array(
        'preis'       => 'preis',
        'objektartid' => 'objektart_id'
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
        // TODO: Implement getDetailCall() method.
    }
}
