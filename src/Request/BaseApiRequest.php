<?php

namespace Justimmo\Api\Request;

abstract class BaseApiRequest implements EntityRequest
{
    /**
     * @const string[] Available filters for current api request class
     */
    const FILTERS = [];

    /**
     * @const string[] Available sort for current api request class
     */
    const SORTS = [];

    /**
     * const string[] Available fields for current api request
     */
    const FIELDS = [];

    /**
     * @const string
     */
    const ASC = 'asc';

    /**
     * @const string
     */
    const DESC = 'desc';

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $sorts = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * Return the path prefix
     *
     * @return string
     */
    abstract protected function getPathPrefix();

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
        if (!empty($this->sorts)) {
            $this->query['sort'] = implode(',', array_unique($this->sorts));
        }
        if (!empty($this->fields)) {
            $this->query['fields'] = implode(',', array_unique($this->fields));
        }

        return $this->query;
    }

    /**
     * @inheritdoc
     */
    public function getPath()
    {
        return $this->getPathPrefix() . $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getGuzzleOptions()
    {
        return [];
    }

    /**
     * Returns a single result by id or multiple results filtered by id
     *
     * @param mixed $id
     * @param bool  $showSiblingIds
     *
     * @return $this
     */
    public function filterById($id, $showSiblingIds = false)
    {
        if (is_array($id)) {
            return $this->filterBy('id', $id);
        }

        $this->path = '/' . $id;

        if ($showSiblingIds) {
            $this->setQueryParameter('showSiblingIds', true);
        }

        return $this;
    }

    /**
     * Sets a filter query parameter
     * Overrides the existing parameter if already exists
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
     * Adds a filter query parameter
     * Merges the existing parameter if already exists
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return $this
     */
    protected function addFilterParameter($field, $value)
    {
        if (empty($this->query['f']) || !array_key_exists($field, $this->query['f'])) {
            return $this->filterBy($field, $value);
        }

        $originalValue = is_array($this->query['f'][$field])
            ? $this->query['f'][$field]
            : [$this->query['f'][$field]];

        if (!is_array($value)) {
            $value = [$value];
        }

        $values = array_unique(array_merge($originalValue, $value));

        return $this->filterBy($field, $values);
    }

    /**
     * Adds parameter for sorting the results
     *
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function sortBy($field, $direction = self::ASC)
    {
        $this->sorts[] = $direction == self::ASC ? $field : '-' . $field;

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
        $this->sorts = [];

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
     * Sets limit and offset for the query in page notation
     *
     * @param int $page
     * @param int $maxPerPage
     *
     * @return $this
     */
    public function paginate($page, $maxPerPage = 10)
    {
        return $this
            ->limit($maxPerPage)
            ->offset(($page - 1) * $maxPerPage);
    }

    /**
     * Sets fields parameter, overwrites current fields variable
     * This parameter tells the api to return additional fields in the response
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function fields($fields)
    {
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * Add a single field to the response
     *
     * @param string $field
     *
     * @return $this
     */
    public function with($field)
    {
        $this->fields[] = $field;

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
     * Adds a subrequest
     *
     * @param string     $name
     * @param SubRequest $request
     *
     * @return $this
     */
    protected function addSubRequest($name, SubRequest $request)
    {
        return $this->filterBy($name, $request->getSubFilters());
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
            $field = lcfirst(substr($method, 8));

            if (in_array($field, static::FILTERS)) {
                if (!isset($arguments[0])) {
                    throw new \InvalidArgumentException(__METHOD__ . ' is missing parameter 1.');
                }

                return $this->filterBy($field, $arguments[0]);
            }
        }

        if (strpos($method, 'sortBy') === 0) {
            $field = lcfirst(substr($method, 6));

            if (in_array($field, static::SORTS)) {
                $direction = !empty($arguments[0]) ? $arguments[0] : self::ASC;

                return $this->sortBy($field, $direction);
            }
        }

        if (strpos($method, 'with') === 0) {
            $field = lcfirst(substr($method, 4));

            if (in_array($field, static::FIELDS)) {
                return $this->with($field);
            }
        }

        throw new \BadMethodCallException("Method \"$method\" is not defined on \"" . get_class($this) . "\".");
    }
}
