<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApiInterface;

class MockJustimmoApi implements JustimmoApiInterface
{
    /**
     * @var string[]
     */
    private $returnValues;

    /**
     * MockJustimmoApi constructor.
     *
     * @param array $returnValues
     */
    public function __construct($returnValues = [])
    {
        $this->returnValues = $returnValues;
    }

    /**
     * Sets a return value for a specific call
     *
     * @param string $call
     * @param string $returnValue
     */
    public function setReturnValue($call, $returnValue = null)
    {
        $this->returnValues[$call] = $returnValue;
    }

    /**
     * Returns a return value for a call
     *
     * @param string $call
     * @param string $default
     *
     * @return string
     */
    private function getReturnValue($call, $default = null)
    {
        return !empty($this->returnValues[$call]) ? $this->returnValues[$call] : $default;
    }

    public function callRealtyList(array $params = [])
    {
        return $this->getReturnValue('realtyList');
    }


    public function callRealtyIds(array $params = [])
    {
        return $this->getReturnValue('realtyIds');
    }

    public function callRealtyDetail($pk, array $params = [])
    {
        return $this->getReturnValue('realtyDetail');
    }

    public function callEmployeeList(array $params = [])
    {
        return $this->getReturnValue('employeeList');
    }

    public function callEmployeeDetail($pk)
    {
        return $this->getReturnValue('employeeDetail');
    }

    public function callProjectList(array $params = [])
    {
        return $this->getReturnValue('projectList');
    }

    public function callProjectDetail($pk, array $params = [])
    {
        return $this->getReturnValue('projectDetail');
    }

    public function callCountries(array $params = [])
    {
        return $this->getReturnValue('countries');
    }

    public function callFederalStates(array $params = [])
    {
        return $this->getReturnValue('federalStates');
    }

    public function callZipCodes(array $params = [])
    {
        return $this->getReturnValue('zipCodes');
    }

    public function callRegions(array $params = [])
    {
        return $this->getReturnValue('regions');
    }

    public function callPoliticalDistricts(array $params = []): string
    {
        return $this->getReturnValue('politicalDistricts');
    }

    public function callRealtyTypes(array $params = [])
    {
        return $this->getReturnValue('realtyTypes');
    }

    public function callTenant(array $params = [])
    {
        return $this->getReturnValue('tenant');
    }

    public function callRealtyCategories(array $params = [])
    {
        return $this->getReturnValue('realtyCategories');
    }

    public function callExpose($pk, $type = 'Default')
    {
        return $this->getReturnValue('expose');
    }

    public function postRealtyInquiry(array $params = [])
    {
        return $this->getReturnValue('realtyInquiry');
    }

    public function callEmployeeIds(array $params = [])
    {
        return $this->getReturnValue('employeeIds');
    }

    public function callProjectIds(array $params = [])
    {
        return $this->getReturnValue('projectIds');
    }
}
