<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Tests\TestCase;

class ProjectWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());
        $list = $wrapper->transformList($this->getFixtures('v1/project_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(2, $list->count());
        $this->assertEquals(2, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Project', $entry);
        }

        /** @var \Justimmo\Model\Project $entry */
        $entry = $list[0];

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Duis ultrices consequat odio quis dapibus', $entry->getDescription());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals(1, count($entry->getAttachments()));
        $this->assertEquals(4, $entry->countRealties());

        $realties = $entry->getRealties();
        /** @var \Justimmo\Model\Realty $realty */
        $realty = $realties[0];

        $this->assertInstanceOf('\Justimmo\Model\Realty', $realty);
        $this->assertEquals(60000, $realty->getTotalRent());
        $this->assertEquals(2460, $realty->getZipCode());
        $this->assertEquals($entry->getId(), $realty->getProjectId());
        $this->assertEquals('verkauft', $realty->getStatus());
    }
}