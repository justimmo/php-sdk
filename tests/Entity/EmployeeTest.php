<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Geo\Address;
use Justimmo\Api\Entity\Attachment;
use Justimmo\Api\Entity\Geo\Country;
use Justimmo\Api\Entity\Employee;
use Justimmo\Api\Entity\Link;
use Justimmo\Api\Request\EmployeeRequest;

class EmployeeTest extends EntityTestCase
{

    const FIXTURE_FILE = 'entity/employee.json';

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new EmployeeRequest();
    }

    /**
     * @inheritdoc
     *
     * @param Employee $entity
     */
    protected function doTestEntity($entity)
    {
        $this->assertInstanceOf(Employee::class, $entity);
        $this->assertEquals(264110, $entity->getId());
        $this->assertEquals('John Doe', $entity->getName());
        $this->assertEquals('John', $entity->getFirstName());
        $this->assertEquals('Doe', $entity->getLastName());
        $this->assertEquals('john.doe@justimmo.at', $entity->getEmail());
        $this->assertEquals('12345678', $entity->getPhone());
        $this->assertEquals('Geschäftsführung', $entity->getPosition());

        $this->assertEquals([
            361675,
            361674,
            361672,
            361671,
            361670,
        ], $entity->getRealtyIds());

        $address = $entity->getAddress();
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Mahü', $address->getStreet());
        $this->assertEquals('1070', $address->getZipCode());
        $this->assertEquals('Wien', $address->getCity());

        $country = $address->getCountry();
        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('AT', $country->getId());
        $this->assertEquals('Österreich', $country->getName());

        $profilePic = $entity->getProfilePicture();
        $this->assertInstanceOf(Attachment::class, $profilePic);
        $this->assertEquals('4004005', $profilePic->getId());
        $this->assertEquals('https://files.justimmo.at/public/pic/orig/APRil3QC47.jpg', $profilePic->getUrl());
        $this->assertEquals('https://files.justimmo.at/public/pic/small/APRil3QC47.jpg', $profilePic->getUrlForConfig('small'));
        $this->assertEmpty($profilePic->getTitle());
        $this->assertEmpty($profilePic->getDescription());
        $this->assertTrue($profilePic->isTypePicture());
        $this->assertFalse($profilePic->isTypeVideo());
        $this->assertFalse($profilePic->isTypeDocument());
        $this->assertEquals(22, $profilePic->getGroup());
        $this->assertTrue($profilePic->isGroupPictures());
        $this->assertFalse($profilePic->isGroupPlans());
        $this->assertFalse($profilePic->isGroupDocuments());
        $this->assertFalse($profilePic->isGroupVideos());
        $this->assertFalse($profilePic->isGroupDocuments());

        $pictures = $entity->getPictures();
        $this->assertEquals(1, count($pictures));
        $picture = $pictures[0];
        $this->assertInstanceOf(Attachment::class, $picture);
        $this->assertEquals('20864645', $picture->getId());
        $this->assertEquals('https://files.justimmo.at/public/pic/orig/BPl6F4-4pB.jpg', $picture->getUrl());
        $this->assertEquals('Wolf', $picture->getTitle());
        $this->assertEquals('weiss', $picture->getDescription());
        $this->assertEquals('pictures-1.jpeg', $picture->getFilename());
        $this->assertTrue($picture->isTypePicture());
        $this->assertFalse($picture->isTypeVideo());
        $this->assertFalse($picture->isTypeDocument());
        $this->assertEquals(22, $picture->getGroup());
        $this->assertTrue($picture->isGroupPictures());
        $this->assertFalse($picture->isGroupPlans());
        $this->assertFalse($picture->isGroupDocuments());
        $this->assertFalse($picture->isGroupVideos());
        $this->assertFalse($picture->isGroupDocuments());

        $links = $entity->getLinks();
        $this->assertEquals(7, count($links));

        $link = $links[0];
        $this->assertEquals(23, $link->getId());
        $this->assertEquals('http://www.google.at', $link->getUrl());
        $this->assertEquals('Suchmaschiene', $link->getDescription());
        $this->assertEquals(Link::TYPE_LINK, $link->getType());
        $this->assertTrue($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertFalse($link->isSocialNetwork());

        $link = $links[1];
        $this->assertEquals(24, $link->getId());
        $this->assertEquals('http://www.youtube.com', $link->getUrl());
        $this->assertEquals('Videos', $link->getDescription());
        $this->assertEquals(Link::TYPE_MOVIE, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertTrue($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertFalse($link->isSocialNetwork());

        $link = $links[2];
        $this->assertEquals(25, $link->getId());
        $this->assertEquals('http://www.facebook.com', $link->getUrl());
        $this->assertEquals('FB', $link->getDescription());
        $this->assertEquals(Link::TYPE_FACEBOOK, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertTrue($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertTrue($link->isSocialNetwork());

        $link = $links[3];
        $this->assertEquals(26, $link->getId());
        $this->assertEquals('http://www.twitter.com', $link->getUrl());
        $this->assertEquals('TW', $link->getDescription());
        $this->assertEquals(Link::TYPE_TWITTER, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertTrue($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertTrue($link->isSocialNetwork());

        $link = $links[4];
        $this->assertEquals(27, $link->getId());
        $this->assertEquals('http://www.xing.com', $link->getUrl());
        $this->assertEquals('Xing', $link->getDescription());
        $this->assertEquals(Link::TYPE_XING, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertTrue($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertTrue($link->isSocialNetwork());

        $link = $links[5];
        $this->assertEquals(28, $link->getId());
        $this->assertEquals('http://www.linkedin.com', $link->getUrl());
        $this->assertEquals('LinkedIn', $link->getDescription());
        $this->assertEquals(Link::TYPE_LINKEDIN, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertTrue($link->isLinkedIn());
        $this->assertFalse($link->isInstagram());
        $this->assertTrue($link->isSocialNetwork());

        $link = $links[6];
        $this->assertEquals(29, $link->getId());
        $this->assertEquals('http://www.instagram.com', $link->getUrl());
        $this->assertEquals('Instagram', $link->getDescription());
        $this->assertEquals(Link::TYPE_INSTAGRAM, $link->getType());
        $this->assertFalse($link->isLink());
        $this->assertFalse($link->isMovie());
        $this->assertFalse($link->isTour());
        $this->assertFalse($link->isFacebook());
        $this->assertFalse($link->isTwitter());
        $this->assertFalse($link->isXing());
        $this->assertFalse($link->isLinkedIn());
        $this->assertTrue($link->isInstagram());
        $this->assertTrue($link->isSocialNetwork());


        $categories = $entity->getEmployeeCategories();
        $this->assertEquals(1, count($categories));
        $this->assertEquals(5254, $categories[0]->getId());
        $this->assertEquals("Geschäftsführung", $categories[0]->getName());
    }
}
