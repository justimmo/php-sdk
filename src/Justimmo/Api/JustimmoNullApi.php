<?php
namespace Justimmo\Api;

/**
 * Class JustimmoNullApi
 *
 * dummy class to satisfy dependency injection on Unit Tests
 *
 * @package Justimmo\Api
 */
class JustimmoNullApi implements JustimmoApiInterface
{
    public function callRealtyList(array $params = array())
    {

    }

    public function callRealtyIds(array $params = array())
    {

    }

    public function callRealtyDetail($pk)
    {

    }

    public function callEmployeeList(array $params = array())
    {

    }

    public function callEmployeeDetail($pk)
    {

    }

    public function callProjectList(array $params = array())
    {

    }

    public function callProjectDetail($pk)
    {

    }

    public function callCountries(array $params = array())
    {

    }

    public function callFederalStates(array $params = array())
    {

    }

    public function callZipCodes(array $params = array())
    {

    }

    public function callRegions(array $params = array())
    {

    }

    public function callRealtyTypes(array $params = array())
    {

    }

    public function callRealtyCategories(array $params = array())
    {

    }

    public function callExpose($pk)
    {

    }

    public function postRealtyInquiry(array $params = array())
    {

    }
}
