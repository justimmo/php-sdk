<?php
namespace Justimmo\Model\Query;

use Justimmo\Model\ModelInterface;
use Justimmo\Pager\PagerInterface;

interface QueryInterface
{
    /**
     * Runs the query and returns the results of the first page, then clears the query.
     *
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @return PagerInterface
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function find();

    /**
     * Runs the query with a limit of one and returns the first result, or null if there is none.
     *
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @return ModelInterface|null
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function findOne();

    /**
     * Runs the query for one page of results, then clears the query.
     *
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @param int $page
     * @param int $maxPerPage
     *
     * @return PagerInterface
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function paginate($page = 1, $maxPerPage = 10);

    /**
     * Fetches one record by its primary key. Filters and sorting on the query do not apply.
     *
     * Every exception the sdk raises implements Justimmo\Exception\JustimmoException, so one catch
     * covers the api failures as well as the two declared below.
     *
     * @param int $pk
     *
     * @return ModelInterface
     *
     * @throws \Justimmo\Exception\MethodNotFoundException in strict mode, when a filter the api does not have was set
     * @throws \Justimmo\Exception\InvalidFilterValueException in strict mode, when a value the api cannot take was set
     */
    public function findPk($pk);

    /**
     * Drops every filter and parameter set on the query.
     *
     * @return $this
     */
    public function clear();
}
