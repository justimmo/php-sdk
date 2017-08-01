<?php

namespace Justimmo\Api\Tests\Request;


use Justimmo\Api\Entity\Inquiry;
use Justimmo\Api\Request\InquiryRequest;

class InquiryRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    protected function getRequest($email = null)
    {
        return new InquiryRequest($email);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCall()
    {
        $this->getRequest(null)->seRealty();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCall2()
    {
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
            ->setEmployee(20);

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
            ],
        ], $request->getGuzzleOptions());
    }
}

