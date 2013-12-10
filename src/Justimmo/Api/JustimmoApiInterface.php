<?php
namespace Justimmo\Api;

interface JustimmoApiInterface
{
    /**
     * make a call to the objectlist with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callObjektList(array $params = array());

    /**
     * calls the detail information of a single objekt
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callObjektDetail($pk);
}
