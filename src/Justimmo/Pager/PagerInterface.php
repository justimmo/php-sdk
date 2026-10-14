<?php

namespace Justimmo\Pager;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface PagerInterface
 *
 * what a list result offers a caller, so find() and paginate() can name a type in 2.x
 *
 * @package Justimmo\Pager
 *
 * @extends ArrayAccess<int, mixed>
 * @extends IteratorAggregate<int, mixed>
 */
interface PagerInterface extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * checks if there has to be pagination
     *
     * @return bool
     */
    public function haveToPaginate();

    /**
     * @return int
     */
    public function getFirstPage();

    /**
     * @return int
     */
    public function getLastPage();

    /**
     * a range of page numbers around the current page, for rendering a pagination
     *
     * @return list<int>
     */
    public function getLinks(int $nb_links = 5);

    /**
     * @return int
     */
    public function getMaxPerPage();

    /**
     * the overall number of results, not the number on this page
     *
     * @return int
     */
    public function getNbResults();

    /**
     * @return int
     */
    public function getPage();

    /**
     * @return $this
     */
    public function setMaxPerPage(int $maxPerPage);

    /**
     * @return $this
     */
    public function setNbResults(int $nbResults);

    /**
     * @return $this
     */
    public function setPage(int $page);

    /**
     * builds an associative array from the list, by calling two getters on each entry
     *
     * @return array<int|string, mixed>
     */
    public function toKeyValue(string $keyGetter, string $valueGetter);
}
