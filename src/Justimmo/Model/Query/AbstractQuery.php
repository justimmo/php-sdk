<?php

namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractQuery implements QueryInterface
{
    protected $pictureSizes = array(
        'small',
        's220x155',
        's312x208',
        'medium_unbranded',
        'big_unbranded',
        'big2_unbranded',
        'fullhd_unbranded',
        'fullhd_unbranded_downscale',
        'medium',
        'big',
        'big2',
        'fullhd',
        'fullhd_downscale',
        'orig',
        'user_small',
        'user_medium',
    );

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
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * returns the method name what should be called on the api class for a list call
     *
     * @return string
     */
    abstract public function getListCall();

    /**
     * returns the method name what should be called on the api class for a detail call
     *
     * @return string
     */
    abstract public function getDetailCall();

    /**
     * Returns the method name what should be called on the api class for a ids call
     *
     * @return string
     */
    abstract public function getIdsCall();

    /**
     * @param JustimmoApiInterface                     $api
     * @param \Justimmo\Model\Wrapper\WrapperInterface $wrapper
     * @param \Justimmo\Model\Mapper\MapperInterface   $mapper
     */
    public function __construct(JustimmoApiInterface $api, WrapperInterface $wrapper, MapperInterface $mapper)
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
     * @param int $page
     * @param int $maxPerPage
     *
     * @return \Justimmo\Pager\ListPager
     */
    public function paginate($page = 1, $maxPerPage = 10)
    {
        $this
            ->setLimit($maxPerPage)
            ->setOffset(($page - 1) * $maxPerPage);

        $pager = $this->find();
        $pager->setPage($page);
        $pager->setMaxPerPage($maxPerPage);

        return $pager;
    }

    /**
     * @return \Justimmo\Pager\ListPager
     */
    public function find()
    {
        $method   = $this->getListCall();
        $response = $this->api->$method($this->params);

        $return = $this->wrapper->transformList($response);

        $this->clear();

        return $return;
    }

    /**
     * @return \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project|null
     */
    public function findOne()
    {
        $this->setLimit(1);
        $pager = $this->find();

        return $pager->offsetGet(0);
    }

    /**
     * @param int $pk
     *
     * @return \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project
     */
    public function findPk($pk)
    {
        $params = array();
        if (isset($this->params['picturesize'])) {
            $params['picturesize'] = $this->params['picturesize'];
        }
        if (isset($this->params['alleProjektObjekte'])) {
            $params['alleProjektObjekte'] = $this->params['alleProjektObjekte'];
        }
        if (isset($this->params['objektIds'])) {
            $params['objektIds'] = $this->params['objektIds'];
        }

        $method   = $this->getDetailCall();
        $response = $this->api->$method($pk, $params);

        $return = $this->wrapper->transformSingle($response);

        $this->clear();

        return $return;
    }

    /**
     * Get a array of realty, employee or project ids
     *
     * @return array
     */
    public function findIds()
    {
        $method   = $this->getIdsCall();
        $response = $this->api->$method($this->params);

        return json_decode($response);
    }

    /**
     * sets the limit parameter
     *
     * @param $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        return $this->set('limit', $limit);
    }

    /**
     * sets the offset parameter
     *
     * @param $offset
     *
     * @return mixed
     */
    public function setOffset($offset)
    {
        return $this->set('offset', $offset);
    }

    /**
     * translates and sets the order of a call
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        return $this->order($this->mapper->getFilterPropertyName($column), $direction);
    }

    /**
     * sets order for a call
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function order($column, $direction = 'asc')
    {
        $this->set('orderby', $column);
        $this->set('ordertype', $direction);

        return $this;
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
     * adds a filter column
     *
     * @param      $key
     * @param null $value
     *
     * @return $this
     */
    public function filter($key, $value = null)
    {
        if ($value === null) {
            return $this;
        }

        if (is_array($value)) {
            if (array_key_exists('min', $value)) {
                $this->params['filter'][$key . '_von'] = $value['min'];
            }
            if (array_key_exists('max', $value)) {
                $this->params['filter'][$key . '_bis'] = $value['max'];
            }

            if (array_key_exists('min', $value) || array_key_exists('max', $value)) {
                return $this;
            }
        }

        $this->params['filter'][$key] = $value;

        return $this;
    }

    /**
     * magic call
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     * @throws \Justimmo\Exception\MethodNotFoundException
     */
    public function __call($method, $params)
    {
        if (mb_strpos($method, 'filterBy') === 0 && count($params) == 1) {
            $key = $this->mapper->getFilterPropertyName(mb_substr($method, 8));

            return $this->filter($key, $params[0]);
        }

        if (mb_strpos($method, 'orderBy') === 0 && count($params) <= 1) {
            $key = $this->mapper->getFilterPropertyName(mb_substr($method, 7));

            if (empty($params[0]) || !in_array($params[0], array('asc', 'desc'))) {
                $params[0] = 'asc';
            }

            return $this->order($key, $params[0]);
        }

        throw new MethodNotFoundException('The method ' . $method . ' was not found in ' . get_class($this));
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string|array $picturesize
     *
     * @return $this
     */
    public function setPicturesize($picturesize)
    {
        if (!is_array($picturesize)) {
            $picturesize = array($picturesize);
        }

        foreach ($picturesize as $size) {
            if (!in_array($size, $this->pictureSizes)) {
                $picturesize[] = 'medium';
            }
        }

        $picturesize = array_unique(array_filter($picturesize));

        return $this->set('picturesize', $picturesize);
    }
}
