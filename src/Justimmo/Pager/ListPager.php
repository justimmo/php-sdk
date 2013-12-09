<?php

namespace Justimmo\Pager;

class ListPager extends \ArrayObject
{
    /**
     * max overall results
     *
     * @var int
     */
    protected $nbResults = 0;

    /**
     * current page
     *
     * @var int
     */
    protected $page = 1;

    /**
     * max results per page
     *
     * @var int
     */
    protected $maxPerPage = 10;

    /**
     * checks if there has to be a pagination
     *
     * @return bool
     */
    public function haveToPaginate()
    {
        return (($this->getMaxPerPage() != 0) && ($this->getNbResults() > $this->getMaxPerPage()));
    }

    /**
     * gets the first page
     *
     * @return int
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * gets the last page
     *
     * @return int
     */
    public function getLastPage()
    {
        return (int) ceil($this->getNbResults() / $this->getMaxPerPage());
    }

    /**
     * gets the nearest pages from current page
     *
     * @param int $nb_links
     *
     * @return array
     */
    public function getLinks($nb_links = 5)
    {
        $links = array();
        $tmp = $this->page - floor($nb_links / 2);
        $check = $this->getLastPage() - $nb_links + 1;
        $limit = ($check > 0) ? $check : 1;
        $begin = ($tmp > 0) ? (($tmp > $limit) ? $limit : $tmp) : 1;

        $i = (int) $begin;
        while (($i < $begin + $nb_links) && ($i <= $this->getLastPage())) {
            $links[] = $i++;
        }

        return $links;
    }

    /**
     * @return int
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * @return int
     */
    public function getNbResults()
    {
        return $this->nbResults;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $maxPerPage
     *
     * @return $this
     */
    public function setMaxPerPage($maxPerPage)
    {
        $this->maxPerPage = $maxPerPage;

        return $this;
    }

    /**
     * @param int $nbResults
     *
     * @return $this
     */
    public function setNbResults($nbResults)
    {
        $this->nbResults = $nbResults;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }
}
