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

    /**
     * calls the detail information of a single project
     *
     * @param $pk
     *
     * @return mixed
     */
    public function callProjectDetail($pk);

    /**
     * retrieve a countrie list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callCountries(array $params = array());

    /**
     * retrieve a federal states list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callFederalStates(array $params = array());

    /**
     * retrieve a zip code list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callZipCodes(array $params = array());

    /**
     * retrieve a region list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callRegions(array $params = array());

    /**
     * retrieve a realty type list
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callRealtyTypes(array $params = array());
}
