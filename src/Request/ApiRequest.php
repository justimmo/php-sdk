<?php

namespace Justimmo\Api\Request;

abstract class ApiRequest implements ApiRequestInterface
{
    const FILTERS = [];

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Adds a filter query parameter
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return $this
     */
    public function filterBy($field, $value)
    {
        $this->query['f'][$field] = $value;

        return $this;
    }

    /**
     * Clears request from all query parameters
     *
     * @return $this
     */
    public function clear()
    {
        $this->query = [];

        return $this;
    }

    /**
     * Limit for resultset
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit = null)
    {
        return $this->setIntegerQueryParameter('limit', $limit);
    }

    /**
     * Offset for resultset
     *
     * @param int $offset
     *
     * @return $this
     */
    public function offset($offset = null)
    {
        return $this->setIntegerQueryParameter('offset', $offset);
    }

    /**
     * Adds fields parameter to the request
     * This parameter tells the api to return additional fields in the response
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        $this->query['fields'] = $fields;

        return $this;
    }

    /**
     * Sets a query parameter for the api request. The parameter will be passed as query string
     *
     * @param string $parameter
     * @param string $value
     *
     * @return $this
     */
    public function setQueryParameter($parameter, $value)
    {
        $this->query[$parameter] = $value;

        return $this;
    }

    /**
     * Sets a integer query parameter. If null or negative it will be removed
     *
     * @param string $key
     * @param int    $value
     *
     * @return $this
     */
    protected function setIntegerQueryParameter($key, $value)
    {
        if ($value === null || $value < 0) {
            unset ($this->query[$key]);
        } else {
            $this->query[$key] = (int) $value;
        }

        return $this;
    }

    /**
     * Intercept magic filter Methods
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments = [])
    {
        if (strpos($method, 'filterBy') === 0) {
            $filter = lcfirst(substr($method, 8));

            if (in_array($filter, static::FILTERS) && count($arguments) === 1) {
                return $this->filterBy($filter, $arguments[0]);
            }
        }

        throw new \BadMethodCallException("Method \"$method\" is not defined on \"" . get_class($this) . "\".");
    }
}
