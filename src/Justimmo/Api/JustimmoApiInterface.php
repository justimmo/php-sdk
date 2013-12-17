<?php
namespace Justimmo\Api;

interface JustimmoApiInterface
{
    /**
     * make a call to the realty list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callRealtyList(array $params = array());

    /**
     * calls the detail information of a single realty
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callRealtyDetail($pk);

    /**
     * make a call to the team list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callEmployeeList(array $params = array());

    /**
     * calls the detail information of a single employee
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callEmployeeDetail($pk);

    /**
     * make a call to the project list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callProjectList(array $params = array());

}
