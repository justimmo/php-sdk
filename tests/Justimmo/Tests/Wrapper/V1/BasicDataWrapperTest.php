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

    public function testFederalStates()
    {
        $list = $this->wrapper->transformFederalStates($this->getFixtures('v1/federal_states.xml'));

        $this->assertEquals(9, count($list));
        $this->assertEquals('Salzburg', $list[130]['name']);
        $this->assertEquals(17, $list[130]['countryId']);
        $this->assertEquals('05', $list[130]['fipsCode']);
    }
}
