<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Request\BaseApiRequest;
use Justimmo\Api\Tests\TestCase;

abstract class RequestTestCase extends TestCase
{
    const SORTS = [];

    const FILTERS = [];

    const FIELDS = [];

    const ENTITY_CLASS = null;

    const METHOD = 'GET';

    const PATH_PREFIX = '/';

    /**
     * @return \Justimmo\Api\Request\BaseApiRequest
     */
    abstract protected function getRequest();

    public function testGetEntityClass()
    {
        if (!empty(static::ENTITY_CLASS)) {
            $this->assertEquals(static::ENTITY_CLASS, $this->getRequest()->getEntityClass());
        }
    }

    public function testGetMethod()
    {
        $this->assertEquals(static::METHOD, $this->getRequest()->getMethod());
    }

    public function testGetPath()
    {
        $request = $this->getRequest();
        $this->assertEquals(static::PATH_PREFIX, $request->getPath());

        $request->filterById(15);
        $this->assertEquals(static::PATH_PREFIX . '/15', $request->getPath());
    }

    public function testPaginate()
    {
        $request = $this->getRequest();
        $request
            ->limit(15)
            ->offset(30);

        $this->assertEquals([
            'limit'  => 15,
            'offset' => 30,
        ], $request->getQuery());

        $request->limit(null);
        $this->assertEquals([
            'offset' => 30,
        ], $request->getQuery());

        $request->paginate(3, 25);
        $this->assertEquals([
            'limit'  => 25,
            'offset' => 50,
        ], $request->getQuery());
    }

    public function testFields()
    {
        $request = $this->getRequest();

        $request->fields('field1');
        $this->assertEquals([
            'fields' => 'field1',
        ], $request->getQuery());

        $request->fields('field1,field2');
        $this->assertEquals([
            'fields' => 'field1,field2',
        ], $request->getQuery());

        $request->fields(['field1']);
        $this->assertEquals([
            'fields' => 'field1',
        ], $request->getQuery());

        $request->fields(['field1', 'field2']);
        $this->assertEquals([
            'fields' => 'field1,field2',
        ], $request->getQuery());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testNotSupportedFilterMethod()
    {
        $this->getRequest()->filterByDoesNotExists(1);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testNotSupportedSortMethod()
    {
        $this->getRequest()->sortByDoesNotExists('asc');
    }

    public function testSortBy()
    {
        $multiRequest = $this->getRequest();
        $multiQuery   = [];

        foreach (static::SORTS as $i => $sort) {
            $method = 'sortBy' . ucfirst($sort);

            $request = $this->getRequest();
            $request->$method();
            $this->assertEquals([
                'sort' => $sort,
            ], $request->getQuery());

            $request->clear();
            $request->$method(BaseApiRequest::ASC);
            $this->assertEquals([
                'sort' => $sort,
            ], $request->getQuery());

            $request->clear();
            $request->$method(BaseApiRequest::DESC);
            $this->assertEquals([
                'sort' => '-' . $sort,
            ], $request->getQuery());

            $ascending = $i % 2 === 0;
            $multiRequest->$method($ascending ? BaseApiRequest::ASC : BaseApiRequest::DESC);
            $multiQuery[] = $ascending ? '' . $sort : '-' . $sort;
        }

        $this->assertEquals([
            'sort' => implode(',', $multiQuery),
        ], $multiRequest->getQuery());
    }

    public function testFilterBy()
    {
        foreach (static::FILTERS as $i => $filter) {
            $method = 'filterBy' . ucfirst($filter);

            //single value passed
            $request = $this->getRequest();
            $request->$method(10);
            $this->assertEquals([
                'f' => [
                    $filter => 10,
                ],
            ], $request->getQuery());

            //multi value
            $request->clear();
            $request->$method([10, 20]);
            $this->assertEquals([
                'f' => [
                    $filter => [10, 20],
                ],
            ], $request->getQuery());

            //min, max
            $request->clear();
            $request->$method(['min' => 10, 'max' => 20]);
            $this->assertEquals([
                'f' => [
                    $filter => [
                        'min' => 10,
                        'max' => 20,
                    ],
                ],
            ], $request->getQuery());

            //min, max
            $request->clear();
            $request->$method(['not' => 10]);
            $this->assertEquals([
                'f' => [
                    $filter => [
                        'not' => 10,
                    ],
                ],
            ], $request->getQuery());
        }
    }

    public function testWith()
    {
        if (empty(static::FIELDS)) {
            return;
        }

        $request   = $this->getRequest();
        $setFields = [];

        foreach (static::FIELDS as $i => $field) {
            $method = 'with' . ucfirst($field);

            $request->$method();
            $setFields[] = $field;
        }

        $this->assertEquals([
            'fields' => implode(',', $setFields),
        ], $request->getQuery());
    }
}

