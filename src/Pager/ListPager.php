<?php

namespace Justimmo\Api\Pager;

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
     * ListPager constructor.
     *
     * @param array $data       Data of current page
     * @param int   $nbResults  Number of results overall pages
     * @param int   $limit      Max results per page
     * @param int   $offset     First result starts at
     *
     * @return static
     */
    public static function create(array $data = [], $nbResults, $limit = null, $offset = null)
    {
        if ($limit === null) {
            $limit = count($data);
        }
        if ($offset === null) {
            $offset = count($data);
        }

        return (new static($data))
            ->setNbResults($nbResults)
            ->setMaxPerPage($limit)
            ->setPage((int) (ceil($offset / $limit) + 1));
    }

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
     * @param int $nbLinks
     *
     * @return array
     */
    public function getLinks($nbLinks = 5)
    {
        $links = array();
        $tmp   = $this->page - floor($nbLinks / 2);
        $check = $this->getLastPage() - $nbLinks + 1;
        $limit = ($check > 0) ? $check : 1;
        $begin = ($tmp > 0) ? (($tmp > $limit) ? $limit : $tmp) : 1;
        $i     = (int) $begin;
        while (($i < $begin + $nbLinks) && ($i <= $this->getLastPage())) {
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
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setMaxPerPage($maxPerPage)
    {
        if ($maxPerPage <= 0) {
            throw new \InvalidArgumentException('$maxPerPage cannot be zero or below');
        }
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

    /**
     * Converts the data into a key/value store array
     *
     * @param $keyGetter
     * @param $valueGetter
     *
     * @return array
     */
    public function toKeyValue($keyGetter, $valueGetter)
    {
        $return = [];
        foreach ($this as $value) {
            if (!method_exists($value, $keyGetter)) {
                throw new \BadMethodCallException('Method ' . $keyGetter . ' not found on ' . get_class($value));
            }
            if (!method_exists($value, $valueGetter)) {
                throw new \BadMethodCallException('Method ' . $valueGetter . ' not found on ' . get_class($value));
            }
            $return[$value->$keyGetter()] = $value->$valueGetter();
        }

        return $return;
    }
}
