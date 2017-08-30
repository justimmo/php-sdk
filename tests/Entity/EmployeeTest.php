<?php

namespace Justimmo\Api\Tests\Entity;

use Justimmo\Api\Entity\Address;
use Justimmo\Api\Entity\Attachment;
use Justimmo\Api\Entity\Country;
use Justimmo\Api\Entity\Employee;
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
        $this->assertEquals(2, count($links));

        $this->assertEquals(23, $links[0]->getId());
        $this->assertEquals('http://www.google.at', $links[0]->getUrl());
        $this->assertEquals('Suchmaschiene', $links[0]->getDescription());
        $this->assertTrue($links[0]->isLink());
        $this->assertFalse($links[0]->isMovieLink());
        $this->assertFalse($links[0]->isTourLink());

        $this->assertEquals(24, $links[1]->getId());
        $this->assertEquals('http://www.youtube.com', $links[1]->getUrl());
        $this->assertEquals('Videos', $links[1]->getDescription());
        $this->assertFalse($links[1]->isLink());
        $this->assertTrue($links[1]->isMovieLink());
        $this->assertFalse($links[1]->isTourLink());

        $categories = $entity->getEmployeeCategories();
        $this->assertEquals(1, count($categories));
        $this->assertEquals(5254, $categories[0]->getId());
        $this->assertEquals("Geschäftsführung", $categories[0]->getName());
    }
}
