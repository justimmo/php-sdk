<?php
namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * override to create your mapping
     *
     * @var array
     */
    protected $filterMapping = array();

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
    protected $listWrapper;

    /**
     * @var WrapperInterface
     */
    protected $detailWrapper;

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
     * @param JustimmoApiInterface $api
     * @param WrapperInterface     $listWrapper
     * @param WrapperInterface     $detailWrapper
     */
    public function __construct(JustimmoApiInterface $api, WrapperInterface $listWrapper, WrapperInterface $detailWrapper)
    {
        $this->api           = $api;
        $this->listWrapper   = $listWrapper;
        $this->detailWrapper = $detailWrapper;
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

        return $pager;
    }

    /**
     * @return \Justimmo\Pager\ListPager
     */
    public function find()
    {
        $method = $this->getListCall();
        $response = $this->api->$method($this->params);

        return $this->listWrapper->transform($response);
    }

    /**
     * @return \Justimmo\Model\Objekt
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
     * @return \Justimmo\Model\Objekt
     */
    public function findPk($pk)
    {
        $method = $this->getDetailCall();
        $response = $this->api->$method($pk);

        return $this->detailWrapper->transform($response);
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
        return $this->setOffset($offset);
    }

    /**
     * sets the order of a call
     *
     * @param $column
     * @param string $direction
     *
     * @return $this
     */
    public function setOrderBy($column, $direction = 'asc')
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
     * @param $key
     * @param null $value
     *
     * @return $this
     */
    public function filter($key, $value = null)
    {
        if (is_array($value)) {
            if (array_key_exists('min', $value)) {
                $this->params['filter'][$key . '_von'] = $value['min'];
            }
            if (array_key_exists('max', $value)) {
                $this->params['filter'][$key . '_bis'] = $value['max'];
            }

            return $this;
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
            $key = mb_strtolower(mb_substr($method, 8));
            if (array_key_exists($key, $this->filterMapping)) {
                return $this->filter($this->filterMapping[$key], $params[0]);
            }
        }

        throw new MethodNotFoundException('The method ' . $method . ' was not found in ' . get_class($this));
    }
}
