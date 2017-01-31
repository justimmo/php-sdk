<?php
namespace Justimmo\Api;

interface JustimmoApiInterface
{
    /**
     * make a call to the realty list with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyList(array $params = array());

    /**
     * make a call to the realty ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyIds(array $params = array());

    /**
     * calls the detail information of a single realty
     *
     * @param $pk
     *
     * @return string
     */
    public function callRealtyDetail($pk);

    /**
     * make a call to the team list with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callEmployeeList(array $params = array());

    /**
     * calls the detail information of a single employee
     *
     * @param $pk
     *
     * @return string
     */
    public function callEmployeeDetail($pk);

    /**
     * Make a call to the employee ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callEmployeeIds(array $params = array());

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
     * @return string
     */
    public function callProjectDetail($pk);

    /**
     * Make a call to the project ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callProjectIds(array $params = array());

    /**
     * retrieve a countrie list
     *
     * @param array $params
     *
     * @return string
     */
    public function callCountries(array $params = array());

    /**
     * retrieve a federal states list
     *
     * @param array $params
     *
     * @return string
     */
    public function callFederalStates(array $params = array());

    /**
     * retrieve a zip code list
     *
     * @param array $params
     *
     * @return string
     */
    public function callZipCodes(array $params = array());

    /**
     * retrieve a region list
     *
     * @param array $params
     *
     * @return string
     */
    public function callRegions(array $params = array());

    /**
     * retrieve a realty type list
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyTypes(array $params = array());

    /**
     * retrieve a realtycategories list
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyCategories(array $params = array());

    /**
     * retrieves the expose for a realty
     *
     * @param   $pk
     * @param   $type
     *
     * @return string
     */
    public function callExpose($pk, $type = 'Default');

    /**
     * makes a request to the api to create a realty inquiry to the contact person of the realty
     *
     * @param array $params
     *
     * @return string
     */
    public function postRealtyInquiry(array $params = array());
}
