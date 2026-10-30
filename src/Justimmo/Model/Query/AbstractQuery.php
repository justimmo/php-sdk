<?php

namespace Justimmo\Model\Query;

use JsonException;
use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Api\StrictModeInterface;
use Justimmo\Exception\InvalidFilterValueException;
use Justimmo\Exception\InvalidResponseException;
use Justimmo\Exception\MethodNotFoundException;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Mapper\OrderMappingInterface;
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
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @param int $page
     * @param int $maxPerPage
     *
     * @return \Justimmo\Pager\ListPager
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
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
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @return \Justimmo\Pager\ListPager
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function find()
    {
        $this->assertTheRequestIsSupported();

        $method   = $this->getListCall();
        $response = $this->api->$method($this->params);

        $return = $this->wrapper->transformList($response);

        $this->clear();

        return $return;
    }

    /**
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @return \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project|null
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function findOne()
    {
        $this->setLimit(1);
        $pager = $this->find();

        return $pager->offsetGet(0);
    }

    /**
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @param int $pk
     *
     * @return \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project
     *
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a picture size the api does not know was set
     */
    public function findPk($pk)
    {
        // the detail call sends none of the filters and not all(), so only the picture size is
        // judged, against what the detail call documents
        $this->assertTheStoredPictureSizeIsSupported($this->getDetailCall());

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
     * Only picturesize is carried over, and ProjectQuery widens this. The api client adds culture
     * itself. JustimmoApi::callEmployeeDetail() takes no parameters, so it sends none of them to
     * team/detail.
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
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @return array
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function findIds()
    {
        $this->assertTheRequestIsSupported();

        $params = $this->params;

        // the ids endpoints document neither, and sending limit made findIds() return every id
        // instead of the first few, so a setLimit() before it was silently ignored
        unset($params['limit'], $params['offset']);

        $method   = $this->getIdsCall();
        $response = $this->api->$method($params);

        // a body which is not json used to come back as null, as if the api had found nothing
        try {
            $ids = json_decode($response ?? '', false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidResponseException('The api answered with a body which is not valid JSON: ' . $e->getMessage(), 0, $e);
        }

        // the api answers a list, an empty one when nothing matches; anything else, like an error
        // object or null, must not pass for a result
        if (!is_array($ids)) {
            throw new InvalidResponseException('The api answered with JSON which is not a list of ids');
        }

        return $ids;
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
     * @param int|float|string $limit
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
     * @param int|float|string $offset
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
        // a null or a boolean was sent untranslated before 1.5.0, and the mappers take a string
        if (self::isSentUntranslated($column)) {
            return $this->order($column, $direction);
        }

        return $this->order($this->orderPropertyName($column), $direction);
    }

    /**
     * Whether orderBy() passes a column on as it is, as it did before 1.5.0
     *
     * This is a method of its own because the docblock of orderBy() names the string which 2.0.0
     * will require, and a type check against that docblock would always look false.
     */
    private static function isSentUntranslated(mixed $column): bool
    {
        return $column === null || is_bool($column);
    }

    /**
     * Translates a model property name into the column the api sorts by
     *
     * A mapper which does not separate its sort columns from its filters is asked for the filter
     * name, which is what every mapper did before 1.5.0.
     */
    private function orderPropertyName(string $column): string
    {
        if ($this->mapper instanceof OrderMappingInterface) {
            return $this->mapper->getOrderPropertyName($column);
        }

        return $this->mapper->getFilterPropertyName($column);
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
     * @param string $key
     * @param mixed $value
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
     * @param string|null $key
     * @param mixed $value
     *
     * @return $this
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when the api has no such filter
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when the filter cannot take that value
     */
    public function filter($key, $value = null)
    {
        // a null name was stored under the empty name before 1.5.0, and the checks take a string
        $key ??= '';

        $this->assertFilterIsSupported($key, 'filter[' . $key . ']');
        $this->assertFilterValueSurvivesNormalisation($key, $value);

        $value = $this->normaliseFilterValue($value);

        if ($value === null) {
            return $this;
        }

        // the api answers a single value on a filter which takes nothing but a list with a 422
        if (!is_array($value) && $this->isListOnly($key)) {
            $value = [$value];
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
    protected function assertFilterIsSupported(string $key, string $called): void
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
     *   - a list on a filter which takes a single value, or a list inside a list
     *   - a value outside an enum the spec documents
     *
     * A fourth one only set() can produce: a single value on a filter which takes nothing but a
     * list. filter() wraps it, and the api answers it with a 422 rather than a wider search.
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

            // min and max are the only keys the range branch of filter() reads. Any other one was
            // dropped there, so a typo used to narrow the search to the end which was spelled right
            $unknown = array_diff(array_keys($value), ['min', 'max']);

            if ($unknown !== []) {
                $this->rejectFilterValue($key, $value, 'takes min and max. It does not know '
                    . implode(', ', array_map([$this, 'describeFilterValue'], $unknown)) . '.');
            }

            // each end of a range is one value. No range filter in the spec accepts a list
            foreach (['min', 'max'] as $bound) {
                if (is_array($value[$bound] ?? null)) {
                    $this->rejectFilterValue(
                        $key,
                        $value[$bound],
                        'takes a single value as ' . $bound . ', a list was given.'
                    );
                }
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

        // filter() wraps a single value itself, so one found here came from set()
        if (!is_array($value) && !empty($filter['list_only'])) {
            $this->rejectFilterValue($key, $value, 'takes nothing but a list, a single value was given. '
                . 'Pass a list, or use filter(), which wraps a single value.');
        }

        // a list carries single values. A list inside it reached the wire as filter[plz][0][0]
        if (is_array($value) && array_filter($value, 'is_array') !== []) {
            $this->rejectFilterValue($key, $value, 'takes a list of single values, a nested list was given.');
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
     * Fails in strict mode when anything the query has collected is something the api cannot take
     *
     * Each rule also runs as the value is set, which is where the better message comes from: a
     * magic call can name itself there, and the caller learns of the mistake on the line which
     * made it. But strict() may be switched on after the filters were set, and until this existed
     * everything set beforehand escaped every rule - so the request is checked once more where it
     * is built, which is the one place the mode is always known.
     *
     * The filters are read as the wire sees them, so a range reaches this as its two bounds.
     *
     * @throws MethodNotFoundException
     * @throws InvalidFilterValueException
     */
    private function assertTheRequestIsSupported(): void
    {
        if (!$this->isStrict()) {
            return;
        }

        foreach ($this->params['filter'] ?? [] as $key => $value) {
            $this->assertFilterIsSupported((string) $key, 'filter[' . $key . ']');
            $this->assertFilterValueHasNoObject((string) $key, $value);
            $this->assertFilterValueIsSupported((string) $key, $value);

            // filter() has turned min and max into the _von and _bis names by now, so a range
            // found here came from set(), and the api ignores filter[preis][min]
            if (is_array($value) && (array_key_exists('min', $value) || array_key_exists('max', $value))) {
                $this->rejectFilterValue((string) $key, $value, 'was given a min or a max through set(). Use filter(), '
                    . "or set filter[{$key}_von] and filter[{$key}_bis] yourself.");
            }
        }

        $this->assertTheStoredPictureSizeIsSupported($this->getListCall());

        $this->assertFiltersApplyWithAll();
    }

    /**
     * Fails in strict mode when the picture size the query carries is one the call does not know
     *
     * @throws InvalidFilterValueException
     */
    private function assertTheStoredPictureSizeIsSupported(string $call): void
    {
        if (!$this->isStrict() || !isset($this->params['picturesize'])) {
            return;
        }

        $sizes = $this->params['picturesize'];

        // set() may store a single size. (array) would turn an object into its properties,
        // and one without any into an empty list which passes
        $this->assertPictureSizesAreSupported(is_array($sizes) ? $sizes : [$sizes], $call);
    }

    /**
     * Fails in strict mode when all() is combined with a filter the api will not apply
     *
     * all() asks for every project, whether it still has real-estates which match. The api
     * answers that by applying the project-level filters and dropping the realty-level ones, so a
     * query which asks for projects with a rent of under a thousand and calls all() receives every
     * project instead, with no sign that half the request was discarded.
     *
     * A project-level filter is unaffected and stays silent, which is the common case: asking for
     * every project with a given tag is a legitimate request and the api honours it.
     *
     * The check runs where the request is built rather than in all() or filter(), because the two
     * can be called in either order.
     *
     * @throws InvalidFilterValueException
     */
    private function assertFiltersApplyWithAll(): void
    {
        if (!$this->isStrict() || (int) ($this->params['alle'] ?? 0) !== 1) {
            return;
        }

        $supported = $this->supportedFilters();

        if ($supported === []) {
            return;
        }

        foreach ($this->params['filter'] ?? [] as $key => $value) {
            $filter = $supported[$key] ?? null;

            if ($filter === null && preg_match('/^(.+)_(von|bis)$/', (string) $key, $match)) {
                $filter = $supported[$match[1]] ?? null;
            }

            // an unknown name is the business of assertFilterIsSupported(), not of this rule
            if (!is_array($filter) || ($filter['level'] ?? 'realty') !== 'realty') {
                continue;
            }

            throw new InvalidFilterValueException(
                'filter[' . $key . '] is a realty-level filter and is ignored while all() is set. '
                . 'Remove all(), or drop the filter.',
                (string) $key,
                $value
            );
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
     * Whether the api takes nothing but a list for this filter
     *
     * Read in both modes, unlike supportedFilters(): wrapping a single value judges nothing, it
     * turns a request the api answers with a 422 into one it can serve. A mapper from outside the
     * SDK has no data to read, so its values are sent as they are given.
     */
    private function isListOnly(string $key): bool
    {
        if (!$this->mapper instanceof SupportedParametersInterface) {
            return false;
        }

        return ($this->mapper->getSupportedFilters($this->getListCall())[$key]['list_only'] ?? false) === true;
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
     * Fails in strict mode on a value normaliseFilterValue() is about to throw away
     *
     * An object which cannot say how to become a string is dropped, because serialising it by its
     * public properties would send parameters the api has never heard of. Dropping it without a
     * word is the problem: one filter becomes none, or a list comes back shorter, and the api
     * answers 200 with the rows of a search nobody asked for.
     *
     * null, an empty string and an empty array are left alone. Those are an omitted value, not a
     * wrong one, and a search form with an optional input submits them on every request.
     *
     * @throws InvalidFilterValueException
     */
    private function assertFilterValueSurvivesNormalisation(string $key, mixed $value): void
    {
        if (!$this->isStrict()) {
            return;
        }

        // the normaliser drops an object at any depth, and an array it empties with it, so
        // ['min' => [$object]] used to search on max alone
        if (is_array($value)) {
            foreach ($value as $one) {
                $this->assertFilterValueSurvivesNormalisation($key, $one);
            }

            return;
        }

        if (is_object($value) && !method_exists($value, '__toString')) {
            $this->rejectFilterValue($key, $value, 'cannot take ' . $value::class
                . '. Pass a string, or an object with a __toString().');
        }
    }

    /**
     * Fails on an object in a filter value which set() stored
     *
     * filter() turns a stringable object into its string, so every object found here came from
     * set(), which stores a value as it is given. The url does not call __toString(). It sends the
     * public properties of an object, and an object without any leaves no trace.
     *
     * @throws InvalidFilterValueException
     */
    private function assertFilterValueHasNoObject(string $key, mixed $value): void
    {
        if (is_array($value)) {
            foreach ($value as $one) {
                $this->assertFilterValueHasNoObject($key, $one);
            }

            return;
        }

        if (is_object($value)) {
            $this->rejectFilterValue($key, $value, 'cannot take ' . $value::class
                . ' through set(). Pass a string, or use filter(), which converts an object with a __toString().');
        }
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
        // converted once, here: the check below and filter() would each call __toString() again,
        // and a conversion which reads a stream returns an empty string the second time
        $value = $this->stringifyObjects($value);

        // checked as the caller gave it. Merged into the list below, a range or a nested list
        // comes out as a list which looks valid, and filter() can no longer tell
        $this->assertFilterValueSurvivesNormalisation($key, $value);
        $normalised = $this->normaliseFilterValue($value);

        if ($normalised !== null) {
            $this->assertFilterValueIsSupported($key, $normalised);
        }

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
     * A stringable object as its string, at any depth. Everything else stays as it is, so an
     * object without __toString() is still left to the normaliser and to strict mode
     */
    private function stringifyObjects(mixed $value): mixed
    {
        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        return is_array($value) ? array_map([$this, 'stringifyObjects'], $value) : $value;
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
            $key = $this->orderPropertyName(mb_substr($method, 7));

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
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when the api has no such size
     */
    public function setPicturesize($picturesize)
    {
        if (!is_array($picturesize)) {
            $picturesize = [$picturesize];
        }

        $picturesize = array_unique(array_filter($picturesize));

        $this->assertPictureSizesAreSupported($picturesize, $this->getListCall());

        // only a call which leaves no usable size at all keeps the medium it has always sent
        if ($picturesize === []) {
            $picturesize = ['medium'];
        }

        return $this->set('picturesize', $picturesize);
    }

    /**
     * Fails in strict mode when the api has no picture size of that name
     *
     * The api does not ignore a size it does not know. It replaces it with the fallback of the
     * endpoint, so a typo answers with pictures of a size nobody asked for, and nothing in the
     * response says which size was served.
     *
     * The sizes are read per endpoint from the spec, so a query whose endpoint documents none -
     * the team calls - and a mapper from outside the SDK leave nothing to compare against.
     *
     * @param array<int, mixed> $sizes
     * @param string            $call  the api call whose sizes are compared against
     *
     * @throws InvalidFilterValueException
     */
    private function assertPictureSizesAreSupported(array $sizes, string $call): void
    {
        if (!$this->isStrict() || !$this->mapper instanceof SupportedParametersInterface) {
            return;
        }

        $parameter = $this->mapper->getSupportedParameters($call)['picturesize'] ?? null;
        $enum      = is_array($parameter) && is_array($parameter['enum'] ?? null) ? $parameter['enum'] : [];

        if ($enum === []) {
            return;
        }

        foreach ($sizes as $size) {
            // isInEnum() lets anything which is not a scalar through, and a size is always a name.
            // A list here reached the url as picturesize[0][0]
            if (is_scalar($size) && $this->isInEnum($size, $enum)) {
                continue;
            }

            throw new InvalidFilterValueException(
                'picturesize does not accept ' . $this->describeFilterValue($size)
                . '. The api knows ' . implode(', ', $enum) . '.',
                'picturesize',
                $size
            );
        }
    }
}
