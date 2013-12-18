<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;

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
     * @var WrapperInterface
     */
    protected $wrapper;


    /**
     * @param JustimmoApiInterface                     $api
     * @param \Justimmo\Model\Wrapper\V1\BasicDataWrapper $wrapper
     */
    public function __construct(JustimmoApiInterface $api, BasicDataWrapper $wrapper)
    {
        $this->api     = $api;
        $this->wrapper = $wrapper;
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
        return $this->set('alle', $value === true);
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
