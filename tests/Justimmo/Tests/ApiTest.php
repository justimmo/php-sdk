<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\NullCache;
use Psr\Log\NullLogger;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JustimmoApi
     */
    protected $api;

    public function setUp()
    {
        $this->api = $this->getMockBuilder('Justimmo\Api\JustimmoApi')
            ->setConstructorArgs(array('username', 'password', new NullLogger(), new NullCache()))
            ->setMethods(array('createRequest'))
            ->getMock();
    }

    /**
     * @expectedException \Justimmo\Exception\AuthenticationException
     * @expectedExceptionMessage Bad Username / Password 401
     */
    public function testWrongUserData()
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 401));

        $this->api->callRealtyList();
    }

    /**
     * @expectedException \Justimmo\Exception\NotFoundException
     * @expectedExceptionMessage Api call not found: 404
     */
    public function test404Response()
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 404));

        $this->api->callRealtyList();
    }

    /**
     * @expectedException \Justimmo\Exception\StatusCodeException
     * @expectedExceptionMessage The Api call returned status code 500
     */
    public function test500Response()
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 500));

        $this->api->callRealtyList();
    }

    /**
     * @expectedException \Justimmo\Exception\InvalidRequestException
     * @expectedExceptionMessage The Api call returned status code 400
     */
    public function testInvalidRequestWithoutBody()
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('', 400));

        $this->api->callRealtyList();
    }

    /**
     * @expectedException \Justimmo\Exception\InvalidRequestException
     * @expectedExceptionMessage zimmer_von ["test" is not a number.]
     */
    public function testInvalidRequestWithBody()
    {
        $this->api->method('createRequest')->willReturn(new MockCurlRequest('<justimmo><error>zimmer_von ["test" is not a number.]</error></justimmo>', 400));

        $this->api->callRealtyList();
    }

    public function testGenerateUrl()
    {
        $this->assertEquals('https://api.justimmo.at/rest/v1/objekt/list?culture=de&orderby=preis&filter%5Bpreis_von%5D=500&filter%5Bpreis_bis%5D=1500&filter%5Bobjektart_id%5D=5&filter%5Bplz%5D%5B%5D=1020&filter%5Bplz%5D%5B%5D=1030', $this->api->generateUrl('objekt/list', array(
            'culture' => 'de',
            'orderby' => 'preis',
            'filter'  => array(
                'preis_von'    => 500,
                'preis_bis'    => 1500,
                'objektart_id' => 5,
                'plz'          => array('1020', '1030')
            )
        )));

    }
}
