<?php

namespace Justimmo\Model\Query;

use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Api\StrictModeInterface;
use Justimmo\Exception\InvalidFilterValueException;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Mapper\SupportedParametersInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @deprecated No longer consulted. The api validates the picture size itself and ignores one it
     *             does not know, so this copy of its enum only drifted. Removed in 2.x
     *
     * @var array<int, string>
     */
    protected $pictureSizes = [
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
    ];

    /**
     * @var array
     */
    protected $params = [];

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
     * Strict mode for this query alone. null means the setting of the api is used
     *
     * @var bool|null
     */
    protected $strict = null;

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
     * Switches strict mode on or off for this query, whatever the api is set to
     *
     * In strict mode the SDK rejects a request the api would silently ignore, instead of sending
     * it. Calling it with false opts a single query out of an api which has it on.
     *
     * Strict mode is how 2.0.0 behaves. 2.0.0 removes the switch and checks every request, so
     * switching it on in 1.x shows ahead of time what the upgrade will reject.
     *
     * @return $this
     */
    public function strict(bool $strict = true)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * Whether this query runs in strict mode
     *
     * The query decides when strict() was called on it, otherwise the api does. An api which does
     * not know about strict mode at all is not strict.
     */
    public function isStrict(): bool
    {
        if ($this->strict !== null) {
            return $this->strict;
        }

        return $this->api instanceof StrictModeInterface && $this->api->isStrict();
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
        $params = [];
        foreach ($this->getDetailParams() as $name) {
            if (isset($this->params[$name])) {
                $params[$name] = $this->params[$name];
            }
        }

        $method   = $this->getDetailCall();
        $response = $this->api->$method($pk, $params);

        $return = $this->wrapper->transformSingle($response);

        $this->clear();

        return $return;
    }

    /**
     * The parameters findPk() carries into the detail call
     *
     * A detail call accepts far fewer parameters than a list call, and the api ignores the rest,
     * so a filter or a sort left on the query is dropped instead of being sent. objekt/detail and
     * team/detail document picturesize alone; ProjectQuery widens this.
     *
     * @return array<int, string>
     */
    protected function getDetailParams(): array
    {
        return ['picturesize'];
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
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when the api has no such filter
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when the filter cannot take that value
     */
    public function filter($key, $value = null)
    {
        $this->assertFilterIsSupported($key, 'filter[' . $key . ']');

        $value = $this->normaliseFilterValue($value);

        if ($value === null) {
            return $this;
        }

        $this->assertFilterValueIsSupported($key, $value);

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
     * Fails in strict mode when the api has no filter of that name
     *
     * This is the defect strict mode exists for. Until now an unknown name travelled to the wire,
     * where the api ignored it and answered 200 with the rows of a query that was never filtered,
     * so a typo or a filter the api had dropped looked like a working search.
     *
     * `$called` is what the caller wrote: a magic `filterByFoo()` is reported as the method, a
     * direct filter() call as the parameter. The list call is the yardstick, because that is where
     * filters are sent. A call the generated data does not know, an endpoint which documents no
     * filter at all, and a mapper from outside the SDK leave nothing to compare against — those are
     * let through rather than made unusable.
     *
     * @throws MethodNotFoundException
     */
    private function assertFilterIsSupported(string $key, string $called): void
    {
        $supported = $this->supportedFilters();

        if ($supported === [] || array_key_exists($key, $supported)) {
            return;
        }

        // a range may also be addressed by its wire names, filter[preis_von] and filter[preis_bis],
        // which the generated data folds into one entry under the base name
        if (preg_match('/^(.+)_(von|bis)$/', $key, $match)) {
            $forms = $supported[$match[1]]['forms'] ?? null;

            if (is_array($forms) && in_array('range', $forms, true)) {
                return;
            }
        }

        throw new MethodNotFoundException($called . ' is not a filter this API has.');
    }

    /**
     * Fails in strict mode when the api has the filter but cannot take that value
     *
     * Three things are rejected. The api answers all three the same way today: it discards the
     * parameter and returns the rows of a wider search than the caller asked for.
     *
     *   - a plain value on a filter which only has a range form. filterByPrice(500000) sends
     *     filter[preis], which does not exist; the api has filter[preis_von] and filter[preis_bis]
     *   - a list on a filter which takes a single value
     *   - a value outside an enum the spec documents
     *
     * The value has already been through normaliseFilterValue(), so an empty one never arrives
     * here. The name has already been through assertFilterIsSupported(), so an unknown one is left
     * to that guard rather than reported twice.
     *
     * @throws InvalidFilterValueException
     */
    private function assertFilterValueIsSupported(string $key, mixed $value): void
    {
        $supported = $this->supportedFilters();
        $isBound   = !isset($supported[$key]) && preg_match('/^(.+)_(von|bis)$/', $key, $match) === 1;
        $filter    = $supported[$isBound ? $match[1] : $key] ?? null;

        if (!is_array($filter)) {
            return;
        }

        // filter[preis_von] is one end of a range and carries a single value
        if ($isBound) {
            if (is_array($value)) {
                $this->rejectFilterValue($key, $value, 'is one end of a range and takes a single value, a list was given.');
            }

            return;
        }

        $forms = is_array($filter['forms'] ?? null) ? $filter['forms'] : [];
        $range = is_array($value) && (array_key_exists('min', $value) || array_key_exists('max', $value));

        if ($range) {
            if (!in_array('range', $forms, true)) {
                $this->rejectFilterValue($key, $value, 'has no range, a min or a max was given. It takes a plain value.');
            }

            return;
        }

        if (!in_array('plain', $forms, true)) {
            $this->rejectFilterValue($key, $value, 'is a range and has no plain form. Pass '
                . "['min' => …] or ['max' => …], or set filter[{$key}_von] and filter[{$key}_bis] yourself.");
        }

        if (is_array($value) && !isset($filter['list'])) {
            $this->rejectFilterValue($key, $value, 'takes a single value, a list was given.');
        }

        if (!is_array($filter['enum'] ?? null)) {
            return;
        }

        foreach (is_array($value) ? $value : [$value] as $one) {
            if (!$this->isInEnum($one, $filter['enum'])) {
                $this->rejectFilterValue($key, $one, 'does not accept ' . $this->describeFilterValue($one)
                    . '. The api knows ' . implode(', ', array_map([$this, 'describeFilterValue'], $filter['enum'])) . '.');
            }
        }
    }

    /**
     * The filters the api documents for this query's list call, or none when there is nothing to
     * judge against
     *
     * Empty means "do not judge" rather than "nothing is allowed": the query is not strict, the
     * mapper comes from outside the SDK and cannot be asked, or the endpoint documents no filter.
     *
     * @return array<string, array<string, mixed>>
     */
    private function supportedFilters(): array
    {
        if (!$this->isStrict() || !$this->mapper instanceof SupportedParametersInterface) {
            return [];
        }

        return $this->mapper->getSupportedFilters($this->getListCall());
    }

    /**
     * Whether a value is one the enum allows
     *
     * Compared as strings, because that is what reaches the api: true is sent as 1 and the integer
     * 1 and the string "1" are the same request. Anything which is not a scalar is left alone, so
     * that this never becomes a second type check.
     *
     * @param array<int, mixed> $enum
     */
    private function isInEnum(mixed $value, array $enum): bool
    {
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }

        if (!is_scalar($value)) {
            return true;
        }

        foreach ($enum as $allowed) {
            if (is_scalar($allowed) && (string) $allowed === (string) $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders a value for an exception message, so that "0" and 0 can be told apart
     */
    private function describeFilterValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return '"' . $value . '"';
        }

        return is_scalar($value) ? (string) $value : gettype($value);
    }

    /**
     * @throws InvalidFilterValueException
     */
    private function rejectFilterValue(string $key, mixed $value, string $problem): never
    {
        throw new InvalidFilterValueException('filter[' . $key . '] ' . $problem, $key, $value);
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

            // filter() runs the same check, but by then the name is the mapped one. Checking
            // first reports filterByFoo(), rather than a filter[Foo] the caller never wrote
            $this->assertFilterIsSupported($key, $method . '()');

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
            $picturesize = [$picturesize];
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
