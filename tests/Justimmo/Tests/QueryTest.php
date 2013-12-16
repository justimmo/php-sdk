<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoNullApi;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\NullWrapper;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Justimmo\Api\JustimmoApiInterface
     */
    protected $api;

    /**
     * @var \Justimmo\Model\Wrapper\WrapperInterface
     */
    protected $wrapper;

    public function setUp()
    {
        $this->api     = new JustimmoNullApi();
        $this->wrapper = new NullWrapper();
    }


    public function testSingle()
    {
        $q = new RealtyQuery($this->api, $this->wrapper);
        $q->filterByPreis(455);

        $this->assertEquals(array(
            'filter' => array(
                'preis' => 455
            )
        ), $q->getParams());
    }

    public function testRange()
    {
        $q = new RealtyQuery($this->api, $this->wrapper, $this->wrapper);
        $q->filterByPreis(array('min' => 455, 'max' => 800));

        $this->assertEquals(array(
            'filter' => array(
                'preis_von' => 455,
                'preis_bis' => 800,
            )
        ), $q->getParams());
    }

    public function testMultiple()
    {
        $q = new RealtyQuery($this->api, $this->wrapper, $this->wrapper);
        $q->filterByPreis(array(455, 800));

        $this->assertEquals(array(
            'filter' => array(
                'preis' => array(455, 800)
            )
        ), $q->getParams());
    }

    public function testOrderBy()
    {
        $q = new RealtyQuery($this->api, $this->wrapper, $this->wrapper);
        $q->setOrderBy('preis');

        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'asc'
        ), $q->getParams());

        $q->setOrderBy('preis', 'desc');
        $this->assertEquals(array(
            'orderby'   => 'preis',
            'ordertype' => 'desc'
        ), $q->getParams());
    }

    public function testFull()
    {
        $q = new RealtyQuery($this->api, $this->wrapper, $this->wrapper);
        $q->set('culture', 'de')
            ->setOrderBy('preis')
            ->setLimit(10)
            ->setOffset(5)
            ->filterByBundeslandId(5)
            ->filterByPreis(array('min' => 455, 'max' => 800))
            ->filterByPlz(array('1020', '1030'));

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
        ), $q->getParams());


    }
}
