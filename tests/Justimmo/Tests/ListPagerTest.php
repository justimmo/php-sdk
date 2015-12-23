<?php
namespace Justimmo\Tests;

use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;

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
            436942 => 'Geräumige Präsentationsfläche für Grossobjekte test',
            195429 => 'DEMOOBJEKT!-Seeblick und Golfplatz im Themalkurort Evian am Genfersee',
            195425 => 'DEMOOBJEKT! Grünruhelage in den Kornkammern Wiens',
            195422 => 'DEMOOBJEKT! Modernes Büro in Zentrumsnähe',
            66079  => 'DEMOOBJEKT! Elegantes Büro neben Bristol und Oper',
            66078  => 'DEMOOBJEKT! Preisgünstiges attraktives Büro mit Wien-Panoramablick',
        ), $this->pager->toKeyValue('getId', 'getTitle'));
    }

    public function testPagerOnePage()
    {
        $this->assertFalse($this->pager->haveToPaginate());
        $this->assertEquals(6, $this->pager->count());
        $this->assertEquals(6, $this->pager->getNbResults());
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
