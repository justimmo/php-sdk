<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Tests\TestCase;

/**
 * Class V1ObjektWrapperTest
 *
 * @todo provide more fixtures with different values and values not available here
 * @package Justimmo\Tests
 */
class RealtyWrapperTest extends TestCase
{
    /**
     * @var RealtyWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new RealtyWrapper(new RealtyMapper());
    }


    public function testTransformList()
    {
        $list = $this->wrapper->transformList($this->getFixtures('v1/realty_list.xml'));

        $this->assertInstanceOf('\Justimmo\Pager\ListPager', $list);
        $this->assertEquals(3, $list->count());
        $this->assertEquals(3, $list->getNbResults());
        $this->assertFalse($list->haveToPaginate());

        foreach($list as $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
        }
    }

    public function testTransformSingle()
    {
        /** @var \Justimmo\Model\Realty $objekt */
        $objekt  = $this->wrapper->transformSingle($this->getFixtures('v1/realty_detail.xml'));

        $this->assertInstanceOf('\Justimmo\Model\Realty', $objekt);

        $this->assertEquals(195439, $objekt->getId());
        $this->assertEquals(51, $objekt->getProjectId());
        $this->assertEquals(34, $objekt->getPropertyNumber());
        $this->assertEquals('DEMOOBJEKT! Elegantes Büro neben Bristol und Oper', $objekt->getTitle());
        $this->assertContains('ausgestattetes 1 bis 2 Personenbüro', $objekt->getDescription());
        $this->assertNull($objekt->getTier());
        $this->assertNull($objekt->getDoorNumber());
        $this->assertEquals(2460, $objekt->getZipCode());
        $this->assertEquals('Wien', $objekt->getPlace());
        $this->assertEquals('buero_praxen', $objekt->getRealtyType());

        $this->assertEquals(array(
            'WOHNEN'  => 1,
            'GEWERBE' => 1,
            'ANLAGE'  => 0,
        ), $objekt->getOccupancy());
        $this->assertEquals(array(
            'KAUF'        => 1,
            'MIETE_PACHT' => 1,
        ), $objekt->getMarketingType());
        $this->assertNull($objekt->getStreet());
        $this->assertEmpty($objekt->getHallway());
        $this->assertEmpty($objekt->getLandParcel());
        $this->assertEmpty($objekt->getDistrict());
        $this->assertEquals('AUT', $objekt->getCountry());
        $this->assertEquals(48.0168636, $objekt->getLatitude());
        $this->assertEquals(16.7801812, $objekt->getLongitude());
        $this->assertEquals($objekt->getFloorArea(), 15);
        $this->assertEquals($objekt->getSurfaceArea(), 15);
        $this->assertNull($objekt->getLivingArea());
        $this->assertNull($objekt->getTotalArea());

        $this->assertNull($objekt->getPurchasePrice());
        $this->assertEquals(60000, $objekt->getTotalRent());
        $this->assertNull($objekt->getNetRent());
        $this->assertEquals(50000, $objekt->getAdditionalCharges());
        $this->assertEquals(10000, $objekt->getHeatingCosts());
        $this->assertNull($objekt->getBuildingSubsidies());
        $this->assertNull($objekt->getYield());
        $this->assertNull($objekt->getNetEarningMonthly());
        $this->assertNull($objekt->getNetEarningYearly());
        $this->assertNull($objekt->getTotalRentVat());
        $this->assertNull($objekt->getTransferTax());
        $this->assertNull($objekt->getLandRegistration());
        $this->assertNull($objekt->getContactEstablishmentCosts());
        $this->assertNull($objekt->getSurety());

        $this->assertEquals(2, count($objekt->getZusatzkosten()));
        $i = 1;
        foreach ($objekt->getZusatzkosten() as $key => $zusatzkosten) {
            $this->assertInstanceOf('\Justimmo\Model\Zusatzkosten', $zusatzkosten);
            if ($i == 1) {
                $this->assertEquals('betriebskosten', $key);
                $this->assertEquals('betriebskosten', $zusatzkosten->getName());
                $this->assertEquals(50000, $zusatzkosten->getNetto());
                $this->assertEquals(50000, $zusatzkosten->getBrutto());
            } else {
                $this->assertEquals('heizkosten', $key);
                $this->assertEquals('heizkosten', $zusatzkosten->getName());
                $this->assertEquals(10000, $zusatzkosten->getNetto());
                $this->assertEquals(10000, $zusatzkosten->getBrutto());
            }
            $this->assertEquals(0, $zusatzkosten->getUst());
            $i++;
        }

        $this->assertEquals(8, count($objekt->getPictures()));
        $this->assertEquals(1, count($objekt->getDocuments()));
        $this->assertEquals(0, count($objekt->getVideos()));

        $energiepass = $objekt->getEnergiepass();

        $this->assertInstanceOf('\Justimmo\Model\Energiepass', $energiepass);
        $this->assertEquals('BEDARF', $energiepass->getEpart());
        $this->assertInstanceOf('\DateTime', $energiepass->getGueltigBis());
        $this->assertEquals('2012-09-12', $energiepass->getGueltigBis()->format('Y-m-d'));

        $this->assertEquals(array(
            'angeschl_gastronomie'     => 'HOTELRESTAURANT',
            'ausricht_balkon_terrasse' => 'NORD',
            'boden'                    => 'ESTRICH',
            'brauereibindung'          => 'brauereibindung',
            'sicherheitstechnik'       => 'POLIZEIRUF',
        ), $objekt->getAusstattung());
    }
}
