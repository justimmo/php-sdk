<?php

namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApiInterface;

class MockJustimmoApi implements JustimmoApiInterface
{
    /**
     * @var string[]
     */
    private $returnValues = array();

    /**
     * MockJustimmoApi constructor.
     *
     * @param array $returnValues
     */
    public function __construct($returnValues = array())
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

    public function callRealtyList(array $params = array())
    {
        return $this->getReturnValue('realtyList');
    }


    public function callRealtyIds(array $params = array())
    {
        return $this->getReturnValue('realtyIds');
    }

    public function callRealtyDetail($pk, array $params = array())
    {
        return $this->getReturnValue('realtyDetail');
    }

    public function callEmployeeList(array $params = array())
    {
        return $this->getReturnValue('employeeList');
    }

    public function callEmployeeDetail($pk)
    {
        return $this->getReturnValue('employeeDetail');
    }

    public function callProjectList(array $params = array())
    {
        return $this->getReturnValue('projectList');
    }

    public function callProjectDetail($pk, array $params = array())
    {
        return $this->getReturnValue('projectDetail');
    }

    public function callCountries(array $params = array())
    {
        return $this->getReturnValue('countries');
    }

    public function callFederalStates(array $params = array())
    {
        return $this->getReturnValue('federalStates');
    }

    public function callZipCodes(array $params = array())
    {
        return $this->getReturnValue('zipCodes');
    }

    public function callRegions(array $params = array())
    {
        return $this->getReturnValue('regions');
    }

    public function callRealtyTypes(array $params = array())
    {
        return $this->getReturnValue('realtyTypes');
    }

    public function callTenant(array $params = array())
    {
        return $this->getReturnValue('tenant');
    }

    public function callRealtyCategories(array $params = array())
    {
        return $this->getReturnValue('realtyCategories');
    }

    public function callExpose($pk, $type = 'Default')
    {
        return $this->getReturnValue('expose');
    }

    public function postRealtyInquiry(array $params = array())
    {
        return $this->getReturnValue('realtyInquiry');
    }

    public function callEmployeeIds(array $params = array())
    {
        return $this->getReturnValue('employeeIds');
    }

    public function callProjectIds(array $params = array())
    {
        return $this->getReturnValue('projectIds');
    }
}
