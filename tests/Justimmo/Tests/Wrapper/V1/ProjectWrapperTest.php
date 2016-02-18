<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Tests\TestCase;

class ProjectWrapperTest extends TestCase
{

    public function testTransformList()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());
        $list = $wrapper->transformList($this->getFixtures('v1/project_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(2, $list->count());
        $this->assertEquals(2, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach ($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Project', $entry);
        }

        /** @var \Justimmo\Model\Project $entry */
        $entry = $list[0];

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $entry->getDescription());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals(1, count($entry->getAttachments()));
        $this->assertEquals(4, $entry->countRealties());
        $this->assertTrue($entry->getUnderConstruction());
        $this->assertEquals('Sonstige Angaben Test', $entry->getMiscellaneous());
        $this->assertEquals('Sonnig am Berg', $entry->getLocality());
        $this->assertEquals('Freitext 1 Test', $entry->getFreetext1());

        $realties = $entry->getRealties();
        /** @var \Justimmo\Model\Realty $realty */
        $realty = $realties[0];

        $this->assertInstanceOf('\Justimmo\Model\Realty', $realty);
        $this->assertEquals(60000, $realty->getTotalRent());
        $this->assertEquals(2460, $realty->getZipCode());
        $this->assertEquals($entry->getId(), $realty->getProjectId());
        $this->assertEquals('verkauft', $realty->getStatus());
    }

    public function testTransformSingle()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());

        /** @var \Justimmo\Model\Project $entry */
        $entry = $wrapper->transformSingle($this->getFixtures('v1/project_detail.xml'));

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $entry->getDescription());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals('Kegelgasse', $entry->getStreet());
        $this->assertEquals(16, $entry->getHouseNumber());

        $this->assertFalse($entry->getUnderConstruction());
        $this->assertEquals('Sonstige Angaben Test', $entry->getMiscellaneous());
        $this->assertEquals('Sonnig am Berg', $entry->getLocality());
        $this->assertEquals('Freitext 1 Test', $entry->getFreetext1());

        $this->assertEquals(20, count($entry->getAttachments()));
        $this->assertEquals(10, $entry->countRealties());

        $realties = $entry->getRealties();
        /** @var \Justimmo\Model\Realty $realty */
        $realty = $realties[0];

        $this->assertInstanceOf('\Justimmo\Model\Realty', $realty);
        $this->assertEquals(60000, $realty->getTotalRent());
        $this->assertEquals(2460, $realty->getZipCode());
        $this->assertEquals($entry->getId(), $realty->getProjectId());
        $this->assertEquals('verkauft', $realty->getStatus());

        $this->assertEquals(16, count($entry->getPictures(null)));
        $this->assertEquals(18, count($entry->getPictures()));
        $this->assertEquals(2, count($entry->getDocuments()));
        $this->assertEquals(0, count($entry->getVideos()));
        $this->assertEquals(2, count($entry->getPictures('bilder360')));

        $document = $entry->getDocuments();
        $document = $document[0];
        $this->assertEquals('http://files.justimmo.at/public/doc/AEVaO8q9ve.pdf', $document->getUrl());
        $this->assertEquals('document', $document->getType());
        $this->assertEquals('pdf', $document->getExtension());
        $this->assertEquals('Wandaufbau.pdf', $document->getTitle());

        /** @var \Justimmo\Model\Attachment $pictures */
        $pictures = $entry->getPictures(null);
        $this->assertEquals('Bild 1', $pictures[0]->getTitle());
        $this->assertEquals('Bild 2', $pictures[1]->getTitle());
        $this->assertEquals('Bild 3', $pictures[2]->getTitle());

        $contact = $entry->getContact();
        $this->assertInstanceOf('\Justimmo\Model\Employee', $contact);
        $this->assertEquals(50, $contact->getId());
        $this->assertEquals('Cristian', $contact->getFirstName());
        $this->assertEquals('Busoi', $contact->getLastName());
        $this->assertEquals('cbusoi@bgcc.at', $contact->getEmail());
        $this->assertEquals('+431798620518', $contact->getFax());
        $this->assertEquals(1, count($contact->getAttachments()));
    }

}
