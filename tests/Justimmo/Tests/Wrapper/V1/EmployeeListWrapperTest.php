<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Wrapper\V1\EmployeeListWrapper;
use Justimmo\Tests\TestCase;

class EmployeeListWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new EmployeeListWrapper();
        $list = $wrapper->transform($this->getFixtures('v1/employee_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(5, $list->count());
        $this->assertEquals(5, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Employee', $entry);
        }

        /** @var \Justimmo\Model\Employee $entry */
        $entry = $list[1];

        $this->assertEquals(100123, $entry->getId());
        $this->assertEquals('Alexander', $entry->getFirstName());
        $this->assertEquals('Diem', $entry->getLastName());
        $this->assertEquals('Marketing & Sales', $entry->getPosition());
        $this->assertEquals('Marketing', $entry->getCategory());
        $this->assertEquals('+43 1 888 74 72', $entry->getMobile());
        $this->assertEquals('+43 676 123 45 67', $entry->getPhone());
        $this->assertEquals('+43 767 765 43 21', $entry->getFax());
        $this->assertEquals('a.diem@bgcc.at', $entry->getEmail());
        $this->assertEquals(1, count($entry->getAttachments()));

    }
}
