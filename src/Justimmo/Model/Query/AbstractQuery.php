<?php

namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @deprecated No longer consulted. The api validates the picture size itself and ignores one it
     *             does not know, so this copy of its enum only drifted. Removed in 2.x
     *
     * @var array<int, string>
     */
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
        'uhd',
        'uhd_unbranded',
        'uhd_downscale',
        'uhd_unbranded_downscale',
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
        $page       = max(1, (int) $page);
        $maxPerPage = max(1, (int) $maxPerPage);

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
     * Get an array of realty, employee, or project ids
     *
     * @return array
     */
    public function findIds()
    {
        $params = $this->params;

        // the ids endpoints document neither, and sending limit made findIds() return every id
        // instead of the first few, so a setLimit() before it was silently ignored
        unset($params['limit'], $params['offset']);

        $method   = $this->getIdsCall();
        $response = $this->api->$method($params);

        return json_decode($response);
    }

    /**
     * sets the limit parameter
     *
     * A negative limit can only be a bug and is clamped, which is the same mistake that produced
     * the negative offsets in the api log. Nothing else is touched: the documented range of the
     * limit is the api's to enforce rather than ours to copy, so `0` and a value above the
     * documented maximum are passed on, as is a non numeric one, which the api answers with a 422
     * the caller can see.
     *
     * The comparison happens before the cast on purpose. `(int) -0.5` is `0`, so casting first
     * would let a negative fraction slip through as a limit of zero
     *
     * @param $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        if (is_numeric($limit)) {
            $limit = $limit < 0 ? 1 : (int) $limit;
        }

        return $this->set('limit', $limit);
    }

    /**
     * sets the offset parameter
     *
     * A negative offset can only be a bug and is clamped to 0, which is the whole point of this
     * change. A non numeric value is passed on for the api to reject rather than silently becoming
     * the first page.
     *
     * Compared before the cast for the same reason as in setLimit()
     *
     * @param $offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        if (is_numeric($offset)) {
            $offset = $offset < 0 ? 0 : (int) $offset;
        }

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
     * @param       $key
     * @param mixed $value
     *
     * @return $this
     */
    public function filter($key, $value = null)
    {
        $value = $this->normaliseFilterValue($value);

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
     * Drops from a filter value what the api cannot use: null, an empty string, an empty array and
     * an object which cannot say how to become a string. `0`, `'0'` and `false` are values and stay.
     *
     * An element of an array is dropped by the same rule and an array with nothing left is dropped
     * as a whole, so a search form which submitted one of three empty inputs no longer sends
     * `filter[plz][]=1010&filter[plz][]=`, which the api rejects with a 422.
     *
     * This runs before the min/max handling of filter(), so a range with one empty bound sends only
     * the bound which has a value.
     *
     * @return mixed null when nothing usable is left
     */
    private function normaliseFilterValue(mixed $value): mixed
    {
        // an object is serialised by its public properties, which produces parameters the api has
        // never heard of under a filter name it knows, so only one which is stringable is kept
        if (is_object($value)) {
            return method_exists($value, '__toString') ? (string) $value : null;
        }

        if (is_array($value)) {
            $clean = [];

            // the keys are kept, so that min and max still reach the branch in filter()
            foreach ($value as $key => $element) {
                $element = $this->normaliseFilterValue($element);

                if ($element !== null) {
                    $clean[$key] = $element;
                }
            }

            return $clean === [] ? null : $clean;
        }

        return $value === '' ? null : $value;
    }

    /**
     * Adds a value to a filter which accepts a list, rather than replacing what is already there.
     *
     * Two methods can map onto one wire filter — filterByTag() and filterByRealtyCategory() both
     * build `tag_name` — and calling both used to discard the first silently. A single value is
     * still sent as a scalar, so a query which sets the filter once looks exactly as before.
     *
     * @return $this
     */
    protected function appendFilter(string $key, mixed $value): static
    {
        $values = [];
        if (isset($this->params['filter'][$key])) {
            $values = (array) $this->params['filter'][$key];
        }

        foreach (is_array($value) ? $value : [$value] as $element) {
            $values[] = $element;
        }

        $values = array_values(array_unique($values, SORT_REGULAR));

        return $this->filter($key, count($values) === 1 ? $values[0] : $values);
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

            // the api matches the direction case insensitively, so DESC is valid and is passed
            // on as the caller wrote it. Only a missing direction falls back
            if (!isset($params[0]) || $params[0] === '') {
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

        $picturesize = array_unique(array_filter($picturesize));

        // an unknown size is the api's business, it ignores one it does not know. Only a call
        // which leaves no usable size at all keeps the medium it has always sent
        if ($picturesize === []) {
            $picturesize = ['medium'];
        }

        return $this->set('picturesize', $picturesize);
    }
}
