<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Mapper\MapperInterface;
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
    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var JustimmoApiInterface
     */
    protected $api;

    /**
     * @var BasicDataWrapper
     */
    protected $wrapper;

    /**
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * @param JustimmoApiInterface                              $api
     * @param \Justimmo\Model\Wrapper\BasicDataWrapperInterface $wrapper
     * @param \Justimmo\Model\Mapper\MapperInterface MapperInterface
     */
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
        $this->params = array();

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
        return $this->set($this->mapper->getFilterPropertyName('all'), $value === true);
    }

    /**
     *
     * @param $value
     *
     * @return $this
     */
    public function filterByCountry($value)
    {
        return $this->set($this->mapper->getFilterPropertyName('country'), $value);
    }

    /**
     *
     * @param $value
     *
     * @return $this
     */
    public function filterByFederalState($value)
    {
        return $this->set($this->mapper->getFilterPropertyName('federalState'), $value);
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
     */
    public function findZipCodes()
    {
        $response = $this->api->callZipCodes($this->params);

        $return = $this->wrapper->transformZipCodes($response);

        $this->clear();

        return $return;
    }

    /**
     * @return array
     */
    public function findRegions()
    {
        $response = $this->api->callRegions($this->params);

        $return = $this->wrapper->transformRegions($response);

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

    public function findRealtyCategories()
    {
        $response = $this->api->callRealtyCategories($this->params);

        $return = $this->wrapper->transformRealtyCategories($response);

        $this->clear();

        return $return;
    }

    /**
     * sets a value for a key
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
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
