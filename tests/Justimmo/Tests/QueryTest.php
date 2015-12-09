<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Justimmo\Model\RealtyQuery
     */
    protected $query;

    public function setUp()
    {
        $this->query = new RealtyQuery(new JustimmoNullApi(), new NullWrapper(), new RealtyMapper());
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
}
