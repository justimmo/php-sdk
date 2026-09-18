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

    /**
     * An empty value filters nothing, so sending it only produced a parameter the api either
     * ignores or rejects. `0`, `'0'` and `false` are values and have to survive
     *
     * @dataProvider emptyValueProvider
     */
    public function testAnEmptyValueIsNotSent($value)
    {
        $this->query->clear();
        $this->query->filterByPrice($value);

        $this->assertSame([], $this->query->getParams());
    }

    public function emptyValueProvider()
    {
        return [
            'null'            => [null],
            'empty string'    => [''],
            'empty array'     => [[]],
            'array of empties' => [['', null]],
            'object'          => [new \DateTime('2099-12-31')],
        ];
    }

    /**
     * @dataProvider keptValueProvider
     */
    public function testAValueWhichLooksEmptyButIsNotIsSent($value, $expected)
    {
        $this->query->clear();
        $this->query->filterByPrice($value);

        $this->assertSame(['filter' => ['preis' => $expected]], $this->query->getParams());
    }

    public function keptValueProvider()
    {
        return [
            'zero'        => [0, 0],
            'string zero' => ['0', '0'],
            'false'       => [false, false],
        ];
    }

    /**
     * An empty element used to reach the api as filter[plz][]=, which it rejects with a 422, so
     * one empty input in a search form broke the whole request
     */
    public function testAnEmptyElementOfAnArrayIsDropped()
    {
        $this->query->clear();
        $this->query->filterByZipCode(['1010', '', null]);

        $this->assertSame(['filter' => ['plz' => ['1010']]], $this->query->getParams());
    }

    /**
     * The empty elements have to go before the min/max branch reads the array, otherwise a range
     * with one empty bound still sends it
     */
    public function testARangeWithAnEmptyBoundSendsOnlyTheOtherOne()
    {
        $this->query->clear();
        $this->query->filterByPrice(['min' => '', 'max' => 800]);

        $this->assertSame(['filter' => ['preis_bis' => 800]], $this->query->getParams());
    }

    /**
     * http_build_query() serialises an object by its public properties, which produces parameters
     * the api has never heard of under a filter name it knows. One which is stringable is used
     */
    public function testAStringableObjectIsSentAsItsString()
    {
        $this->query->clear();
        $this->query->filterByKeyword(new class {
            public $ignored = 'not this';

            public function __toString()
            {
                return 'Wohnung';
            }
        });

        $this->assertSame(['filter' => ['stichwort' => 'Wohnung']], $this->query->getParams());
    }

    /**
     * The direction of the caller is passed on as written, the api matches it case insensitively.
     * It used to be rewritten to asc, which reversed the sort the caller asked for
     *
     * @dataProvider directionProvider
     */
    public function testTheSortDirectionIsNotRewritten($given, $expected)
    {
        $this->query->clear();
        $this->query->orderByPrice($given);

        $this->assertSame(
            ['orderby' => 'preis', 'ordertype' => $expected],
            $this->query->getParams()
        );
    }

    public function directionProvider()
    {
        return [
            'lower case'  => ['desc', 'desc'],
            'upper case'  => ['DESC', 'DESC'],
            'mixed case'  => ['Asc', 'Asc'],
            'empty falls back' => ['', 'asc'],
        ];
    }

    /**
     * A negative limit or offset can only be a bug and is clamped, which is what produced the
     * negative offsets in the api log. Two values are deliberately left alone: `0`, because the
     * documented range of limit is the api's to enforce rather than ours to copy, and a non numeric
     * value, which is passed on so the api answers with a 422 the caller can see instead of it
     * silently becoming 0 or 1
     *
     * @dataProvider limitOffsetProvider
     */
    public function testALimitOrOffsetWhichCanOnlyBeABugIsClamped($method, $given, $key, $expected)
    {
        $this->query->clear();
        $this->query->$method($given);

        $this->assertSame([$key => $expected], $this->query->getParams());
    }

    public function limitOffsetProvider()
    {
        return [
            'negative offset'          => ['setOffset', -5, 'offset', 0],
            'negative numeric string'  => ['setOffset', '-3', 'offset', 0],
            // (int) -0.5 is 0, so casting before comparing would let a fraction slip through
            'negative fraction offset' => ['setOffset', -0.5, 'offset', 0],
            'negative fraction limit'  => ['setLimit', -0.5, 'limit', 1],
            'negative exponent limit'  => ['setLimit', '-1e-3', 'limit', 1],
            'zero offset'              => ['setOffset', 0, 'offset', 0],
            'negative limit'           => ['setLimit', -5, 'limit', 1],
            'float limit'              => ['setLimit', 1.9, 'limit', 1],
            'numeric string limit'     => ['setLimit', '5', 'limit', 5],
            // the documented range of limit is the api's to enforce, so 0 is passed on
            'zero limit survives'      => ['setLimit', 0, 'limit', 0],
            // junk is the api's business, it answers with a 422 which the caller can see
            'non numeric limit'        => ['setLimit', 'abc', 'limit', 'abc'],
            'non numeric offset'       => ['setOffset', 'abc', 'offset', 'abc'],
        ];
    }

    /**
     * An unknown picture size is the api's business, it ignores one it does not know. Appending
     * medium next to it meant the caller received a size they never asked for
     */
    public function testAnUnknownPictureSizeIsSentAlone()
    {
        $this->query->clear();
        $this->query->setPicturesize('does-not-exist');

        $this->assertSame(['picturesize' => ['does-not-exist']], $this->query->getParams());
    }

    /**
     * A call which leaves no usable size keeps the medium it has always sent
     */
    public function testAnEmptyPictureSizeStillSendsMedium()
    {
        $this->query->clear();
        $this->query->setPicturesize('');

        $this->assertSame(['picturesize' => ['medium']], $this->query->getParams());
    }

    /**
     * The ids endpoints document neither limit nor offset, and sending limit made findIds() return
     * every id rather than the first few
     */
    public function testFindIdsSendsNeitherLimitNorOffset()
    {
        $api = new class extends JustimmoNullApi {
            /**
             * @var array<string, mixed>|null
             */
            public $params = null;

            public function callRealtyIds(array $params = array()): string
            {
                $this->params = $params;

                return '[]';
            }
        };

        $query = new RealtyQuery($api, new NullWrapper(), new RealtyMapper());
        $query->setLimit(10)->setOffset(20)->filterByZipCode('1010')->findIds();

        $this->assertSame(['filter' => ['plz' => '1010']], $api->params);

        // the query itself keeps them, findIds() has never cleared its parameters
        $this->assertSame(
            ['limit' => 10, 'offset' => 20, 'filter' => ['plz' => '1010']],
            $query->getParams()
        );
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
