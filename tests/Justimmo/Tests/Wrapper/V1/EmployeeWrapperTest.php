<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Tests\TestCase;

class EmployeeWrapperTest extends TestCase
{

    public function testTransform()
    {
        $wrapper = new EmployeeWrapper(new EmployeeMapper());
        $list = $wrapper->transformList($this->getFixtures('v1/employee_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(5, $list->count());
        $this->assertEquals(5, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach ($list as $entry) {
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
        $this->assertEquals(4, count($entry->getAttachments()));
        $this->assertEquals('von der Stange', $entry->getSuffix());
        $this->assertEquals(2, $entry->getNumber());
        $this->assertEquals('Biografie von Alexander Diem', $entry->getBiography());

        $this->assertEquals('Mariahilfer Straße 8/11', $entry->getStreet());
        $this->assertEquals('1070', $entry->getPostal());
        $this->assertEquals('Wien', $entry->getCity());
        $this->assertEquals('https://www.justimmo.at', $entry->getUrl());

        $this->assertInstanceOf('\Justimmo\Model\Attachment', $entry->getProfilePicture());
        $this->assertEquals('http://files.justimmo.at/public/pic/user_medium/ABVB1fBrug.png', $entry->getProfilePicture()->getUrl('medium'));

        $attachments = $entry->getAttachments();
        foreach ($attachments as $attachment) {
            $this->assertInstanceOf('\Justimmo\Model\Attachment', $attachment);
        }

        $attachment = $attachments[0];
        $this->assertEquals('http://files.justimmo.at/public/pic/user_medium/ABVB1fBrug.png', $attachment->getUrl('medium'));
        $this->assertEquals('PROFILBILD', $attachment->getGroup());
        $this->assertEquals('picture', $attachment->getType());

        $attachment = $attachments[1];
        $this->assertEquals('https://files.justimmo.at/public/pic/big/Awf77iCGNE.jpg', $attachment->getUrl());
        $this->assertEmpty($attachment->getGroup());
        $this->assertEquals('picture', $attachment->getType());

        $attachment = $attachments[2];
        $this->assertEquals('https://www.justimmo.at', $attachment->getUrl());
        $this->assertEquals('LINKS', $attachment->getGroup());
        $this->assertEquals('link', $attachment->getType());

        $attachment = $attachments[3];
        $this->assertEquals('http://www.youtube.com', $attachment->getUrl());
        $this->assertEquals('FILMLINK', $attachment->getGroup());
        $this->assertEquals('link', $attachment->getType());

        /** @var \Justimmo\Model\Employee $entry */
        $entry = $list[2];
        $this->assertEmpty($entry->getSuffix());
    }
}
