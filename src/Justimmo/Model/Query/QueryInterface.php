<?php
namespace Justimmo\Model\Query;

use Justimmo\Model\ModelInterface;
use Justimmo\Pager\PagerInterface;

interface QueryInterface
{
    /**
     * Runs the query and returns the results of the first page, then clears the query.
     *
     * @return PagerInterface
     */
    public function find();

    /**
     * Runs the query with a limit of one and returns the first result, or null if there is none.
     *
     * @return ModelInterface|null
     */
    public function findOne();

    /**
     * Runs the query for one page of results, then clears the query.
     *
     * @param int $page
     * @param int $maxPerPage
     *
     * @return PagerInterface
     */
    public function paginate($page = 1, $maxPerPage = 10);

    /**
     * Fetches one record by its primary key. Filters and sorting on the query do not apply.
     *
     * @param int $pk
     *
     * @return ModelInterface
     */
    public function findPk($pk);

    /**
     * Drops every filter and parameter set on the query.
     *
     * @return $this
     */
    public function clear();
}
