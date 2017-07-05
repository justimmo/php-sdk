<?php

namespace Justimmo\Api\Tests;

use Justimmo\Api\ResultSet\Pager;

class PagerTest extends TestCase
{

    protected function getPager()
    {
        $data = include self::FIXTURES_PATH . DIRECTORY_SEPARATOR . 'pager.php';

        return Pager::create($data, 121, 15, 45);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testWrongKeyGetter()
    {
        $this->getPager()->toKeyValue('getTest', 'getTitle');
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testWrongValueGetter()
    {
        $this->getPager()->toKeyValue('getId', 'getTest');
    }

    public function testToKeyValue()
    {
        $this->assertEquals([
            61  => 'Bürofläche',
            45  => 'Wohnung',
            54  => 'Terrassenwohnung',
            103 => 'Erdgeschoß',
            8   => 'Einfamilienhaus',
            19  => 'Bungalow',
            20  => 'Villa',
            15  => 'Reihenendhaus',
            9   => 'Mehrfamilienhaus',
            10  => 'Reihenhaus',
        ], $this->getPager()->toKeyValue('getId', 'getName'));
    }

    public function testPagerOnePage()
    {
        $pager = Pager::create([1, 2, 3, 4, 5, 6, 7], 7, 10, 0);

        $this->assertFalse($pager->haveToPaginate());
        $this->assertEquals(7, $pager->count());
        $this->assertEquals(7, $pager->getNbResults());
        $this->assertEquals(1, $pager->getFirstPage());
        $this->assertEquals(1, $pager->getLastPage());
        $this->assertEquals([1], $pager->getLinks(5));
    }

    public function testPagerMultiPage()
    {
        $pager = $this->getPager();

        $this->assertEquals(4, $pager->getPage());
        $this->assertTrue($pager->haveToPaginate());
        $this->assertEquals(10, $pager->count());
        $this->assertEquals(1, $pager->getFirstPage());
        $this->assertEquals(9, $pager->getLastPage());
        $this->assertEquals([2, 3, 4, 5, 6], $pager->getLinks(5));
    }
}
