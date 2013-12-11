<?php
namespace Justimmo\Tests;

use Justimmo\Model\Wrapper\V1\ObjektListWrapper;

class V1ObjektListWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new ObjektListWrapper();
        $list = $wrapper->transform($this->getFixtures('v1/objekt_list.xml'));
        
        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals($list->count(), count($list));
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Objekt', $entry);
        }
    }
}
