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
    public function callRealtyList(array $params = [])
    {
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo><query-result><count>0</count></query-result></justimmo>';
    }

    public function callRealtyIds(array $params = [])
    {
        return '{}';
    }

    public function callRealtyDetail($pk, array $params = [])
    {
        return $this->emptyXML();
    }

    public function callEmployeeList(array $params = [])
    {
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo></justimmo>';
    }

    public function callEmployeeDetail($pk)
    {
        return $this->emptyXML();
    }

    public function callProjectList(array $params = [])
    {
        return '<?xml version="1.0" encoding="UTF-8"?><justimmo><query-result><count>0</count></query-result></justimmo>';
    }

    public function callProjectDetail($pk, array $params = [])
    {
        return $this->emptyXML();
    }

    public function callCountries(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callFederalStates(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callZipCodes(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callRegions(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callPoliticalDistricts(array $params = []): string
    {
        return $this->emptyXML();
    }

    public function callRealtyTypes(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callTenant(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callRealtyCategories(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callExpose($pk, $type = 'Default')
    {
        return null;
    }

    public function postRealtyInquiry(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callEmployeeIds(array $params = [])
    {
        return $this->emptyXML();
    }

    public function callProjectIds(array $params = [])
    {
        return $this->emptyXML();
    }

    protected function emptyXML()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>';
    }
}
