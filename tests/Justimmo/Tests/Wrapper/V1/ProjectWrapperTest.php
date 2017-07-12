<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\ProjectMapper;
use Justimmo\Model\Project;
use Justimmo\Model\Wrapper\V1\ProjectWrapper;
use Justimmo\Tests\TestCase;

class ProjectWrapperTest extends TestCase
{

    public function testTransformList()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());
        /** @var Project[] $list */
        $list = $wrapper->transformList($this->getFixtures('v1/project_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(3, $list->count());
        $this->assertEquals(3, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach ($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Project', $entry);
        }

        $entry = $list[0];

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $entry->getDescription());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals(1, count($entry->getAttachments()));
        $this->assertEquals(4, $entry->countRealties());
        $this->assertFalse($entry->getUnderConstruction());
        $this->assertEquals(Project::PROJECT_STATE_PLANNING, $entry->getProjectState());
        $this->assertTrue($entry->isStatePlanning());
        $this->assertFalse($entry->isStateBuilding());
        $this->assertFalse($entry->isStateFinished());
        $this->assertEquals('Sonstige Angaben Test', $entry->getMiscellaneous());
        $this->assertEquals('Sonnig am Berg', $entry->getLocality());
        $this->assertEquals('Freitext 1 Test', $entry->getFreetext1());
        $this->assertTrue($entry->getIsReference());

        $realties = $entry->getRealties();
        $realty = $realties[0];

        $this->assertInstanceOf('\Justimmo\Model\Realty', $realty);
        $this->assertEquals(60000, $realty->getTotalRent());
        $this->assertEquals(2460, $realty->getZipCode());
        $this->assertEquals($entry->getId(), $realty->getProjectId());
        $this->assertEquals('verkauft', $realty->getStatus());

        $entry = $list[1];
        $this->assertTrue($entry->getUnderConstruction());
        $this->assertEquals(Project::PROJECT_STATE_BUILDING, $entry->getProjectState());
        $this->assertFalse($entry->isStatePlanning());
        $this->assertTrue($entry->isStateBuilding());
        $this->assertFalse($entry->isStateFinished());
        $this->assertFalse($entry->getIsReference());

        $entry = $list[2];
        $this->assertFalse($entry->getUnderConstruction());
        $this->assertEquals(Project::PROJECT_STATE_FINISHED, $entry->getProjectState());
        $this->assertFalse($entry->isStatePlanning());
        $this->assertFalse($entry->isStateBuilding());
        $this->assertTrue($entry->isStateFinished());
    }

    public function testTransformSingle()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());

        /** @var \Justimmo\Model\Project $entry */
        $entry = $wrapper->transformSingle($this->getFixtures('v1/project_detail.xml'));

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals(3, $entry->getProjectNumber());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $entry->getDescription());
        $this->assertEquals('AUT', $entry->getCountry());
        $this->assertEquals('Wien', $entry->getFederalState());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals('Kegelgasse', $entry->getStreet());
        $this->assertEquals(16, $entry->getHouseNumber());

        $this->assertEquals(5, $entry->getTierCount());
        $this->assertEquals(1, $entry->getAtticCount());
        $this->assertEquals(2, $entry->getStyleOfBuildingId());
        $this->assertEquals(2017, $entry->getYearOfConstruction());
        $this->assertEquals('absolute Ruhelage', $entry->getNoiseLevel());
        $this->assertEquals('Anfang 2018', $entry->getAvailability());
        $this->assertEquals('Erstbezug', $entry->getCondition());
        $this->assertEquals('Erstbezug', $entry->getHouseCondition());
        $this->assertEquals('sehr gut', $entry->getAreaAssessment());
        $this->assertEquals('gut', $entry->getPropertyAssessment());
        $this->assertEquals(array(
            'wohnen'  => true,
            'gewerbe' => true,
            'anlage'  => false,
        ), $entry->getOccupancy());

        $this->assertTrue($entry->getUnderConstruction());
        $this->assertEquals(Project::PROJECT_STATE_BUILDING, $entry->getProjectState());
        $this->assertEquals('Im Bau', $entry->getProjectStateSemantic());
        $this->assertEquals('Sonstige Angaben Test', $entry->getMiscellaneous());
        $this->assertEquals('Sonnig am Berg', $entry->getLocality());
        $this->assertEquals('Freitext 1 Test', $entry->getFreetext1());
        $this->assertEquals('Freitext 2 Test', $entry->getFreetext2());
        $this->assertEquals('Nähe Test', $entry->getProximity());

        $this->assertEquals(20, count($entry->getAttachments()));
        $this->assertEquals(10, $entry->countRealties());

        $this->assertInstanceOf('\Datetime', $entry->getEpcValidUntil(null));
        $this->assertEquals('2027-06-01', $entry->getEpcValidUntil());
        $this->assertEquals('123', $entry->getEpcHeatingDemand());
        $this->assertEquals('A++', $entry->getEpcHeatingDemandClass());
        $this->assertEquals('4.56', $entry->getEpcEnergyEfficiencyFactor());
        $this->assertEquals('A+', $entry->getEpcEnergyEfficiencyFactorClass());

        $this->assertEquals('http://www.justimmo.at', $entry->getUrl());
        $this->assertInstanceOf('\Datetime', $entry->getCompletionDate(null));
        $this->assertInstanceOf('\Datetime', $entry->getSaleStart(null));
        $this->assertInstanceOf('\Datetime', $entry->getCreatedAt(null));
        $this->assertEquals('2017-01-15', $entry->getCompletionDate());
        $this->assertEquals('2017-02-01', $entry->getSaleStart());
        $this->assertEquals('2017-06-01 08:00:00', $entry->getCreatedAt());

        $this->assertEquals(16, count($entry->getPictures(null)));
        $this->assertEquals(18, count($entry->getPictures()));
        $this->assertEquals(2, count($entry->getDocuments()));
        $this->assertEquals(0, count($entry->getVideos()));
        $this->assertEquals(2, count($entry->getPictures('bilder360')));

        $realties = $entry->getRealties();

        /** @var \Justimmo\Model\Realty $realty */
        $realty = $realties[0];

        $this->assertInstanceOf('\Justimmo\Model\Realty', $realty);
        $this->assertEquals(60000, $realty->getTotalRent());
        $this->assertEquals(2460, $realty->getZipCode());
        $this->assertEquals($entry->getId(), $realty->getProjectId());
        $this->assertEquals('verkauft', $realty->getStatus());

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
        $this->assertEquals(123, $contact->getNumber());
        $this->assertEquals('Cristian', $contact->getFirstName());
        $this->assertEquals('Busoi', $contact->getLastName());
        $this->assertEquals('cbusoi@bgcc.at', $contact->getEmail());
        $this->assertEquals('+431798620518', $contact->getFax());
        $this->assertEquals(1, count($contact->getAttachments()));
        $this->assertInstanceOf('\Justimmo\Model\Attachment', $contact->getProfilePicture());
        $this->assertEquals('http://files.justimmo.at/public/pic/medium/ABJIukIH3R.jpg', $contact->getProfilePicture()->getUrl('medium'));
    }

    public function testTransformWithRealtyIds()
    {
        $wrapper = new ProjectWrapper(new ProjectMapper());

        /** @var \Justimmo\Model\Project $entry */
        $entry = $wrapper->transformSingle($this->getFixtures('v1/project_detail_ids.xml'));

        $this->assertEquals(51, $entry->getId());
        $this->assertEquals('Neubau mitten im dritten', $entry->getTitle());
        $this->assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $entry->getDescription());
        $this->assertEquals('1030', $entry->getZipCode());
        $this->assertEquals('Wien', $entry->getPlace());
        $this->assertEquals('Kegelgasse', $entry->getStreet());
        $this->assertEquals(16, $entry->getHouseNumber());

        $this->assertTrue($entry->getUnderConstruction());
        $this->assertEquals(Project::PROJECT_STATE_BUILDING, $entry->getProjectState());
        $this->assertEquals('Sonstige Angaben Test', $entry->getMiscellaneous());
        $this->assertEquals('Sonnig am Berg', $entry->getLocality());
        $this->assertEquals('Freitext 1 Test', $entry->getFreetext1());

        $this->assertEquals(20, count($entry->getAttachments()));
        $this->assertEquals(10, $entry->countRealties());

        $this->assertEquals('http://www.justimmo.at', $entry->getUrl());
        $this->assertInstanceOf('\Datetime', $entry->getCompletionDate(null));
        $this->assertInstanceOf('\Datetime', $entry->getSaleStart(null));
        $this->assertEquals('2017-01-15', $entry->getCompletionDate());
        $this->assertEquals('2017-02-01', $entry->getSaleStart());

        $this->assertEmpty($entry->getRealties());
        $this->assertEquals(array(
            195439,
            195438,
            195437,
            195430,
            195427,
            195424,
            66077,
            66076,
            66046,
            66040,
        ), $entry->getRealtyIds());
    }

}
