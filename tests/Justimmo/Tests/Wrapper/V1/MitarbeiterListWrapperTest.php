<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Wrapper\V1\MitarbeiterListWrapper;
use Justimmo\Tests\TestCase;

class MitarbeiterListWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new MitarbeiterListWrapper();
        $list = $wrapper->transform($this->getFixtures('v1/mitarbeiter_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(5, $list->count());
        $this->assertEquals(5, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Mitarbeiter', $entry);
        }

        /** @var \Justimmo\Model\Mitarbeiter $entry */
        $entry = $list[1];

        $this->assertEquals(100123, $entry->getId());
        $this->assertEquals('Alexander', $entry->getVorname());
        $this->assertEquals('Diem', $entry->getNachname());
        $this->assertEquals('Marketing & Sales', $entry->getPosition());
        $this->assertEquals('Marketing', $entry->getKategorie());
        $this->assertEquals('+43 1 888 74 72', $entry->getHandy());
        $this->assertEquals('+43 676 123 45 67', $entry->getTel());
        $this->assertEquals('+43 767 765 43 21', $entry->getFax());
        $this->assertEquals('a.diem@bgcc.at', $entry->getEmail());
        $this->assertEquals(1, count($entry->getAttachments()));

    }
}
