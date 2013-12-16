<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Wrapper\V1\RealtyListWrapper;
use Justimmo\Tests\TestCase;

class RealtyListWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new RealtyListWrapper();
        $list = $wrapper->transform($this->getFixtures('v1/realty_list.xml'));
        
        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(3, $list->count());
        $this->assertEquals(3, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
        }
    }
}
