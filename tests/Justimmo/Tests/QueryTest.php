<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    /**
     * @var RealtyQuery
     */
    protected $query;

    protected RecordingJustimmoApi $api;

    /**
     * A query with a wrapper which returns a real pager, needed for paginate()
     */
    protected RealtyQuery $pagerQuery;

    public function setUp(): void
    {
        $this->query = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new RealtyMapper());

        $this->api        = new RecordingJustimmoApi();
        $this->pagerQuery = new RealtyQuery($this->api, new RealtyWrapper(new RealtyMapper()), new RealtyMapper());
    }

    public function testSingle()
    {
        $this->query->clear();

        $this->query->filterByPrice(455);

        $this->assertEquals(array(
            'filter' => array(
                'preis' => 455
            )
        ), $this->query->getParams());
    }

    public function testRange()
    {
        $this->query->clear();
        $this->query->filterByPrice(array('min' => 455, 'max' => 800));

        $this->assertEquals(array(
            'filter' => array(
                'preis_von' => 455,
                'preis_bis' => 800,
            )
        ), $this->query->getParams());
    }

    public function testMultiple()
    {
        $this->query->clear();
        $this->query->filterByPrice(array(455, 800));

        $this->assertEquals(array(
            'filter' => array(
                'preis' => array(455, 800)
            )
        ), $this->query->getParams());
    }

    public function testOrderBy()
    {
        $this->query->clear();
        $this->query->orderBy('Price');

        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'asc'
        ), $this->query->getParams());

        $this->query->orderBy('Price', 'desc');
        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'desc'
        ), $this->query->getParams());
    }

    public function testOrderByCall()
    {
        $this->query->clear();

        $this->query->orderByPrice();
        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'asc'
        ), $this->query->getParams());

        $this->query->orderByPrice('desc');
        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'desc'
        ), $this->query->getParams());

        $this->query->orderByCreatedAt('desc');
        $this->assertEquals(array(
            'orderby'   => 'created_at',
            'ordertype' => 'desc'
        ), $this->query->getParams());
    }

    public function testFull()
    {
        $this->query->clear();
        $this->query->set('culture', 'de')
            ->orderBy('Price')
            ->setLimit(10)
            ->setOffset(5)
            ->filterByFederalStateId(5)
            ->filterByPrice(array('min' => 455, 'max' => 800))
            ->filterByZipCode(array('1020', '1030'));

        $this->assertEquals(array(
            'limit'     => 10,
            'offset'    => 5,
            'culture'   => 'de',
            'orderby'   => 'preis',
            'ordertype' => 'asc',
            'filter'    => array(
                'preis_von'     => 455,
                'preis_bis'     => 800,
                'bundesland_id' => 5,
                'plz'           => array('1020', '1030')
            )
        ), $this->query->getParams());

    }

    /**
     * paginate() must never send a negative offset to the api, no matter what page is given
     *
     * @dataProvider paginateProvider
     */
    public function testPaginate($page, $maxPerPage, $expectedOffset, $expectedLimit, $expectedPage)
    {
        $pager  = $this->pagerQuery->paginate($page, $maxPerPage);
        $params = $this->api->getLastParams();

        $this->assertSame($expectedOffset, $params['offset']);
        $this->assertSame($expectedLimit, $params['limit']);
        $this->assertSame($expectedPage, $pager->getPage());
        $this->assertSame($expectedLimit, $pager->getMaxPerPage());
    }

    public function paginateProvider()
    {
        return array(
            'first page'            => array(1, 100, 0, 100, 1),
            'second page'           => array(2, 100, 100, 100, 2),
            'page zero'             => array(0, 100, 0, 100, 1),
            'negative page'         => array(-1, 100, 0, 100, 1),
            'numeric string page'   => array('3', 100, 200, 100, 3),
            'string page zero'      => array('0', 100, 0, 100, 1),
            'null page'             => array(null, 100, 0, 100, 1),
            'non numeric page'      => array('abc', 100, 0, 100, 1),
            'float page'            => array(1.9, 100, 0, 100, 1),
            'zero max per page'     => array(1, 0, 0, 1, 1),
            'negative max per page' => array(1, -10, 0, 1, 1),
        );
    }

    public function testPaginateDefaults()
    {
        $pager  = $this->pagerQuery->paginate();
        $params = $this->api->getLastParams();

        $this->assertSame(0, $params['offset']);
        $this->assertSame(10, $params['limit']);
        $this->assertSame(1, $pager->getPage());
        $this->assertSame(10, $pager->getMaxPerPage());
    }

    public function testMagicMethodMapping()
    {
        $this->query->clear();
        $this->query
            ->filterByParentId(12345)
            ->filterByRealtySystemType('area');

        $this->assertEquals(array(
            'filter' => array(
                'parent_id'   => 12345,
                'realty_type' => 'area',
            ),
        ), $this->query->getParams());
    }
}
