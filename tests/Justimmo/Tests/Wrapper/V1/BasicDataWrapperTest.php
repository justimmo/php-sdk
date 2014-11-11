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

    public function testZipCodes()
    {
        $list = $this->wrapper->transformZipCodes($this->getFixtures('v1/zip_codes.xml'));

        $this->assertEquals(127, count($list));
        $this->assertEquals('Lofer', $list[537]['place']);
        $this->assertEquals(17, $list[537]['countryId']);
        $this->assertEquals(115, $list[537]['regionId']);
        $this->assertEquals('5090', $list[537]['zipCode']);
        $this->assertEquals(130, $list[537]['federalStateId']);
    }

    public function testRegions()
    {
        $list = $this->wrapper->transformRegions($this->getFixtures('v1/regions.xml'));

        $this->assertEquals(23, count($list));
        $this->assertEquals('17., Hernals', $list[34]);
        $this->assertEquals('12., Meidling', $list[68]);
        $this->assertEquals('2., Leopoldstadt', $list[58]);
        $this->assertEquals('22., Donaustadt', $list[12]);
    }

    public function testRealtyTypes()
    {
        $list = $this->wrapper->transformRealtyTypes($this->getFixtures('v1/realty_types.xml'));

        $this->assertEquals(3, count($list));
        $this->assertEquals('Haus', $list[3]['name']);
        $this->assertEquals('haus', $list[3]['key']);
        $this->assertEquals('haustyp', $list[3]['attribute']);
    }

    public function testRealtyCategories()
    {
        $list = $this->wrapper->transformRealtyCategories($this->getFixtures('v1/categories.xml'));

        $this->assertEquals(4, count($list));
        $this->assertEquals(array(
            3469 => array(
                'name'         => 'Kat 1',
                'sortableRank' => 2
            ),
            3582 => array(
                'name'         => 'Kat 2',
                'sortableRank' => 0
            ),
            3140 => array(
                'name'         => 'Luxusobjekte',
                'sortableRank' => 1
            ),
            2057 => array(
                'name'         => 'Referenzobjekte',
                'sortableRank' => 3
            ),
        ), $list);
    }
}
