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
    public function callRealtyList(array $params = []);

    /**
     * make a call to the realty ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyIds(array $params = []);

    /**
     * calls the detail information of a single realty
     *
     * @param int $pk
     * @param array $params
     *
     * @return string
     */
    public function callRealtyDetail($pk, array $params = []);

    /**
     * make a call to the team list with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callEmployeeList(array $params = []);

    /**
     * calls the detail information of a single employee
     *
     * @param int $pk
     * @param array<string, mixed> $params
     *
     * @return string
     */
    public function callEmployeeDetail($pk, array $params = []);

    /**
     * Make a call to the employee ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callEmployeeIds(array $params = []);

    /**
     * make a call to the project list with a set of given params
     *
     * @param array $params
     *
     * @return mixed
     */
    public function callProjectList(array $params = []);

    /**
     * make a call to the project short list with a set of given params
     *
     * @param array<string, scalar> $params
     *
     * @return string
     */
    public function callProjectShortList(array $params = []): string;

    /**
     * calls the detail information of a single project
     *
     * @param int $pk
     * @param array $params
     *
     * @return string
     */
    public function callProjectDetail($pk, array $params = []);

    /**
     * Make a call to the project ids with a set of given params
     *
     * @param array $params
     *
     * @return string
     */
    public function callProjectIds(array $params = []);

    /**
     * retrieve a countrie list
     *
     * @param array $params
     *
     * @return string
     */
    public function callCountries(array $params = []);

    /**
     * retrieve a federal states list
     *
     * @param array $params
     *
     * @return string
     */
    public function callFederalStates(array $params = []);

    /**
     * retrieve a zip code list
     *
     * @deprecated since 1.8.0, removed in 2.0.0. Use callPoliticalDistricts()
     *
     * @param array $params
     *
     * @return string
     */
    public function callZipCodes(array $params = []);

    /**
     * retrieve a region list
     *
     * @deprecated since 1.8.0, removed in 2.0.0. Use callPoliticalDistricts()
     *
     * @param array $params
     *
     * @return string
     */
    public function callRegions(array $params = []);

    /**
     * Retrieve a list of political districts
     *
     * @param array<string, scalar> $params
     *
     * @return string
     */
    public function callPoliticalDistricts(array $params = []): string;

    /**
     * Retrieve tenant data
     *
     * @param array $params
     *
     * @return string
     */
    public function callTenant(array $params = []);

    /**
     * retrieve a realty type list
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyTypes(array $params = []);

    /**
     * retrieve a realtycategories list
     *
     * @param array $params
     *
     * @return string
     */
    public function callRealtyCategories(array $params = []);

    /**
     * retrieve a realty features list
     *
     * @param array<string, scalar> $params
     *
     * @return string
     */
    public function callRealtyFeatures(array $params = []): string;

    /**
     * retrieves the expose for a realty
     *
     * @param int $pk
     * @param string $type
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
    public function postRealtyInquiry(array $params = []);
}
