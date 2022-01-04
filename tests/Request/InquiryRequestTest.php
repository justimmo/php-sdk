<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Inquiry;
use Justimmo\Api\Request\InquiryRequest;
use PHPUnit\Framework\TestCase;

class InquiryRequestTest extends TestCase
{
    protected function getRequest($email = null)
    {
        return new InquiryRequest($email);
    }

    public function testCall()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->getRequest(null)->seRealty();
    }

    public function testCall2()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->getRequest(null)->setRealty();
    }

    public function testParams()
    {
        $request = $this->getRequest('test@test.at');

        $request->setSalutation(1)
            ->setTitle('Mr')
            ->setFirstName('Test')
            ->setLastName('Tester')
            ->setPhone(1235)
            ->setStreet('Testingstreet')
            ->setZipCode(1020)
            ->setCity('Wien')
            ->setCountry('AT')
            ->setMessage('Test Message')
            ->setRealty(15)
            ->setEmployee(20)
            ->setCategory(2);

        $this->assertEquals(Inquiry::class, $request->getEntityClass());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/inquiry', $request->getPath());
        $this->assertEquals([], $request->getQuery());
        $this->assertEquals([
            'form_params' => [
                'email'      => 'test@test.at',
                'salutation' => 1,
                'title'      => 'Mr',
                'firstName'  => 'Test',
                'lastName'   => 'Tester',
                'phone'      => 1235,
                'street'     => 'Testingstreet',
                'zipCode'    => 1020,
                'city'       => 'Wien',
                'country'    => 'AT',
                'message'    => 'Test Message',
                'realty'     => 15,
                'employee'   => 20,
                'category'   => 2,
            ],
        ], $request->getGuzzleOptions());
    }
}

