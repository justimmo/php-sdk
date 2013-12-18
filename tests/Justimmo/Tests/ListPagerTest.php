<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\NullCache;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Psr\Log\NullLogger;

class ListPagerTest extends TestCase
{
    /**
     * @var \Justimmo\Pager\ListPager
     */
    protected $pager;


    public function setUp()
    {
        $wrapper = new RealtyWrapper(new RealtyMapper());
        $this->pager = $wrapper->transformList($this->getFixtures('v1/realty_list.xml'));
    }

    /**
     * @expectedException \Justimmo\Exception\MethodNotFoundException
     */
    public function testWrongKeyGetter()
    {
        $this->pager->toKeyValue('getTest', 'getTitle');
    }

    /**
     * @expectedException \Justimmo\Exception\MethodNotFoundException
     */
    public function testWrongValueGetter()
    {
        $this->pager->toKeyValue('getId', 'getTest');
    }

    public function testToKeyValue()
    {
        $this->assertEquals(array(
            195439 => 'DEMOOBJEKT! Elegantes Büro neben Bristol und Oper',
            195434 => 'DEMOOBJEKT! Luxus Wienblick-Dachgeschosswohnung mit Wintergarten,  offenen Kamin, Sauna und Weinkeller',
            66076 => 'DEMOOBJEKT! Grünruhelage in den Kornkammern Wiens',
        ), $this->pager->toKeyValue('getId', 'getTitle'));
    }

    public function testPagerOnePage()
    {
        $this->assertFalse($this->pager->haveToPaginate());
        $this->assertEquals(3, $this->pager->count());
        $this->assertEquals(3, $this->pager->getNbResults());
        $this->assertEquals(1, $this->pager->getFirstPage());
        $this->assertEquals(1, $this->pager->getLastPage());
        $this->assertEquals(array(1), $this->pager->getLinks(5));
    }

    public function testPagerMultiPage()
    {
        $this->pager->setNbResults(121);
        $this->pager->setMaxPerPage(15);
        $this->pager->setPage(4);

        $this->assertTrue($this->pager->haveToPaginate());
        $this->assertEquals(1, $this->pager->getFirstPage());
        $this->assertEquals(9, $this->pager->getLastPage());
        $this->assertEquals(array(2,3,4,5,6), $this->pager->getLinks(5));
    }
}
