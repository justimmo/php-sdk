<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Tests\TestCase;

class BasicDataWrapperTest extends TestCase
{
    /**
     * @var BasicDataWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new BasicDataWrapper();
    }


    public function testCountries()
    {
        $list = $this->wrapper->transformCountries($this->getFixtures('v1/countries.xml'));

        $this->assertEquals(246, count($list));
        $this->assertEquals('Deutschland', $list[59]['name']);
        $this->assertEquals('DE', $list[59]['iso2']);
        $this->assertEquals('DEU', $list[59]['iso3']);
    }
}
