<?php
namespace Justimmo\Tests;

use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\NullCache;
use Psr\Log\NullLogger;

class ApiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Justimmo\Exception\AuthenticationException
     */
    public function testWrongUserData()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
        $api->callRealtyList();
    }

    public function testGenerateUrl()
    {
        $api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());

        $this->assertEquals('http://api.justimmo.at/rest/v1/objekt/list?culture=de&orderby=preis&filter%5Bpreis_von%5D=500&filter%5Bpreis_bis%5D=1500&filter%5Bobjektart_id%5D=5&filter%5Bplz%5D%5B%5D=1020&filter%5Bplz%5D%5B%5D=1030', $api->generateUrl('objekt/list', array(
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
