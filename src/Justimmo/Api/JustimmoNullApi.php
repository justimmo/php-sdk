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
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo><query-result><count>0</count></query-result></justimmo>';
    }

    public function callRealtyIds(array $params = array())
    {
        return '{}';
    }

    public function callRealtyDetail($pk)
    {
        return $this->emptyXML();
    }

    public function callEmployeeList(array $params = array())
    {
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo></justimmo>';
    }

    public function callEmployeeDetail($pk)
    {
        return $this->emptyXML();
    }

    public function callProjectList(array $params = array())
    {
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo><query-result><count>0</count></query-result></justimmo>';
    }

    public function callProjectDetail($pk)
    {
        return $this->emptyXML();
    }

    public function callCountries(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callFederalStates(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callZipCodes(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callRegions(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callRealtyTypes(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callRealtyCategories(array $params = array())
    {
        return $this->emptyXML();
    }

    public function callExpose($pk, $type = 'Default')
    {
        return null;
    }

    public function postRealtyInquiry(array $params = array())
    {
        return $this->emptyXML();
    }

    protected function emptyXML()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>';
    }
}
