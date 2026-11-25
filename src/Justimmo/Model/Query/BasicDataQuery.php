<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\PoliticalDistrict;
use Justimmo\Model\RealtyFeature;
use Justimmo\Model\TriggersDeprecations;
use Justimmo\Model\Wrapper\BasicDataWrapperInterface;

/**
 * Class BasicDataQuery
 *
 * this class allows you to filter and retrieve some basic data from justimmo like countries, federal states, etc...
 *
 * @package Justimmo\Model\Query
 */
class BasicDataQuery
{
    use TriggersDeprecations;

    /**
     * @var array
     */
    protected array $params = [];

    protected JustimmoApiInterface $api;
    protected BasicDataWrapperInterface  $wrapper;
    protected MapperInterface $mapper;

    public function __construct(JustimmoApiInterface $api, BasicDataWrapperInterface $wrapper, MapperInterface $mapper)
    {
        $this->api     = $api;
        $this->wrapper = $wrapper;
        $this->mapper  = $mapper;
    }

    /**
     * clears all filter and order parameter
     *
     * @return $this
     */
    public function clear()
    {
        $this->params = [];

        return $this;
    }

    /**
     * defines wether the call should retrieve all available data or only available data where there are active realties
     *
     * @param bool $value
     *
     * @return $this
     */
    public function all($value)
    {
        // 1 or 0, the value the api documents. Only the boolean true switches it on, as always
        return $this->setParameter($this->mapper->getFilterPropertyName('all'), $value === true ? 1 : 0);
    }

    /**
     *
     * @param int|string $value
     *
     * @return $this
     */
    public function filterByCountry($value)
    {
        return $this->setParameter($this->mapper->getFilterPropertyName('country'), $value);
    }

    /**
     *
     * @param int $value
     *
     * @return $this
     */
    public function filterByFederalState($value)
    {
        return $this->setParameter($this->mapper->getFilterPropertyName('federalState'), $value);
    }

    /**
     * Filters the realty features by their group, the parameter gruppe
     *
     * @param int $value the id of the feature group
     */
    public function filterByFeatureGroupId(int $value): static
    {
        return $this->setParameter($this->mapper->getFilterPropertyName('featureGroupId'), $value);
    }

    /**
     * @return array
     */
    public function findCountries()
    {
        $response = $this->api->callCountries($this->params);

        $return = $this->wrapper->transformCountries($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     */
    public function findFederalStates()
    {
        $response = $this->api->callFederalStates($this->params);

        $return = $this->wrapper->transformFederalStates($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     *
     * @deprecated since 1.8.0, removed in 2.0.0. Use findPoliticalDistricts()
     */
    public function findZipCodes()
    {
        $this->triggerDeprecation('findZipCodes', 'findZipCodes() is deprecated and is removed in 2.0.0. Use findPoliticalDistricts().', '1.8.0');

        $response = $this->api->callZipCodes($this->params);

        $return = $this->wrapper->transformZipCodes($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     *
     * @deprecated since 1.8.0, removed in 2.0.0. Use findPoliticalDistricts()
     */
    public function findRegions()
    {
        $this->triggerDeprecation('findRegions', 'findRegions() is deprecated and is removed in 2.0.0. Use findPoliticalDistricts().', '1.8.0');

        $response = $this->api->callRegions($this->params);

        $return = $this->wrapper->transformRegions($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array<int, PoliticalDistrict>
     */
    public function findPoliticalDistricts(): array
    {
        $response = $this->api->callPoliticalDistricts($this->params);

        $return = $this->wrapper->transformPoliticalDistricts($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     */
    public function findRealtyTypes()
    {
        $response = $this->api->callRealtyTypes($this->params);

        $return = $this->wrapper->transformRealtyTypes($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function findRealtyCategories()
    {
        $response = $this->api->callRealtyCategories($this->params);

        $return = $this->wrapper->transformRealtyCategories($response);

        $this->clear();

        return $return;
    }

    /**
     * The features a realty can have, with the account's own ones
     *
     * @return array<int, RealtyFeature>
     */
    public function findRealtyFeatures(): array
    {
        $response = $this->api->callRealtyFeatures($this->params);

        $return = $this->wrapper->transformRealtyFeatures($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     */
    public function findTenant()
    {
        $response = $this->api->callTenant($this->params);

        $return = $this->wrapper->transformTenant($response);

        $this->clear();

        return $return;
    }

    /**
     * sets a value for a key
     *
     * @deprecated Use all(), filterByCountry() or filterByFederalState(). Protected in 2.0.0
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->triggerDeprecation('set', 'set() is deprecated and is protected in 2.0.0. Use all(), filterByCountry() or filterByFederalState().');

        return $this->setParameter($key, $value);
    }

    /**
     * What set() does, for the SDK and for a subclass, without the deprecation notice
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    protected function setParameter(mixed $key, mixed $value): static
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
