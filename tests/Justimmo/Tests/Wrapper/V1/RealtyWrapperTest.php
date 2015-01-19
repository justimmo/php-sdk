<?php
namespace Justimmo\Tests\Wrapper\V1;

use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Realty;
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

        /** @var Realty $entry */
        foreach ($list as $i => $entry) {
            $this->assertInstanceOf('\Justimmo\Model\Realty', $entry);
            if ($i == 1) {
                $this->assertEquals('aktiv', $entry->getStatus());
                $this->assertEquals(5, $entry->getStatusId());
            } else {
                $this->assertEquals('vermittelt', $entry->getStatus());
                $this->assertEquals(8, $entry->getStatusId());
            }
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
        $this->assertEquals(1, $objekt->getDoorNumber());
        $this->assertEquals(1030, $objekt->getZipCode());
        $this->assertEquals('Wien', $objekt->getPlace());
        $this->assertEquals('buero_praxen', $objekt->getRealtyType());

        $this->assertEquals(array(
            'WOHNEN'  => false,
            'GEWERBE' => true,
            'ANLAGE'  => false,
        ), $objekt->getOccupancy());
        $this->assertEquals(array(
            'KAUF'        => true,
            'MIETE_PACHT' => false,
        ), $objekt->getMarketingType());
        $this->assertEquals('Stephansplatz', $objekt->getStreet());
        $this->assertEquals('Am Graben', $objekt->getRegionalAddition());
        $this->assertEmpty($objekt->getHallway());
        $this->assertEmpty($objekt->getLandParcel());
        $this->assertEmpty($objekt->getDistrict());
        $this->assertEquals('AUT', $objekt->getCountry());
        $this->assertEquals(48.2087105, $objekt->getLatitude());
        $this->assertEquals(16.3726546, $objekt->getLongitude());
        $this->assertEquals($objekt->getFloorArea(), 150);
        $this->assertEquals($objekt->getSurfaceArea(), 150);
        $this->assertNull($objekt->getLivingArea());
        $this->assertNull($objekt->getTotalArea());

        $this->assertEquals(450000, $objekt->getPurchasePrice());
        $this->assertEquals(500, $objekt->getAdditionalCharges());
        $this->assertEquals(126, $objekt->getHeatingCosts());
        $this->assertEquals('EUR', $objekt->getCurrency());
        $this->assertEquals('16.200,00 € inkl. 20% USt.', $objekt->getCommission());
        $this->assertNull($objekt->getBuildingSubsidies());
        $this->assertNull($objekt->getYield());
        $this->assertNull($objekt->getNetEarningMonthly());
        $this->assertNull($objekt->getNetEarningYearly());
        $this->assertNull($objekt->getTotalRentVat());
        $this->assertEquals(3.5, $objekt->getTransferTax());
        $this->assertEquals(1.1, $objekt->getLandRegistration());
        $this->assertNull($objekt->getContactEstablishmentCosts());
        $this->assertNull($objekt->getSurety());
        $this->assertNull($objekt->getCompensation());

        $costs = $objekt->getAdditionalCosts();
        $this->assertEquals(4, count($costs));

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['betriebskosten']);
        $this->assertEquals('Betriebskosten', $costs['betriebskosten']->getName());
        $this->assertEquals(500, $costs['betriebskosten']->getNet());
        $this->assertEquals(750, $costs['betriebskosten']->getGross());
        $this->assertEquals(50, $costs['betriebskosten']->getVat());
        $this->assertEquals('numeric', $costs['betriebskosten']->getVatType());
        $this->assertEquals(250, $costs['betriebskosten']->getVatValue());
        $this->assertEquals(250, $costs['betriebskosten']->getVatInput());
        $this->assertFalse($costs['betriebskosten']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['heizkosten']);
        $this->assertEquals('Heizkosten', $costs['heizkosten']->getName());
        $this->assertEquals(126, $costs['heizkosten']->getNet());
        $this->assertEquals(138.6, $costs['heizkosten']->getGross());
        $this->assertEquals(10, $costs['heizkosten']->getVat());
        $this->assertEquals('percent', $costs['heizkosten']->getVatType());
        $this->assertEquals(12.6, $costs['heizkosten']->getVatValue());
        $this->assertEquals(10, $costs['heizkosten']->getVatInput());
        $this->assertFalse($costs['heizkosten']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['zusatzkosten_6']);
        $this->assertEquals('Garage', $costs['zusatzkosten_6']->getName());
        $this->assertEquals(150, $costs['zusatzkosten_6']->getNet());
        $this->assertEquals(150, $costs['zusatzkosten_6']->getGross());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVat());
        $this->assertEquals('percent', $costs['zusatzkosten_6']->getVatType());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVatValue());
        $this->assertEquals(0, $costs['zusatzkosten_6']->getVatInput());
        $this->assertTrue($costs['zusatzkosten_6']->getOptional());

        $this->assertInstanceOf('\Justimmo\Model\AdditionalCosts', $costs['zusatzkosten_8']);
        $this->assertEquals('Liftkosten', $costs['zusatzkosten_8']->getName());
        $this->assertEquals(15, $costs['zusatzkosten_8']->getNet());
        $this->assertEquals(18, $costs['zusatzkosten_8']->getGross());
        $this->assertEquals(20, $costs['zusatzkosten_8']->getVat());
        $this->assertEquals('percent', $costs['zusatzkosten_8']->getVatType());
        $this->assertEquals(3, $costs['zusatzkosten_8']->getVatValue());
        $this->assertEquals(20, $costs['zusatzkosten_8']->getVatInput());
        $this->assertFalse($costs['zusatzkosten_8']->getOptional());


        $this->assertEquals(11, count($objekt->getPictures()));
        $this->assertEquals(10, count($objekt->getPictures(null)));
        $this->assertEquals(1, count($objekt->getPictures('TITELBILD')));
        $this->assertEquals(0, count($objekt->getDocuments()));
        $this->assertEquals(0, count($objekt->getVideos()));

        $pictures = $objekt->getPictures();
        $picture = $pictures[0];
        $this->assertEquals('http://files.justimmo.at/public/pic/big/AHA0s6aAaT.jpg', $picture->getUrl());
        $this->assertEquals('http://files.justimmo.at/public/pic/small/AHA0s6aAaT.jpg', $picture->getUrl('small'));
        $this->assertEquals('jpg', $picture->getExtension());
        $this->assertEquals('picture', $picture->getType());

        $energiepass = $objekt->getEnergyPass();

        $this->assertInstanceOf('\Justimmo\Model\EnergyPass', $energiepass);
        $this->assertEquals('BEDARF', $energiepass->getEpart());
        $this->assertInstanceOf('\DateTime', $energiepass->getValidUntil());
        $this->assertEquals('2012-09-12', $energiepass->getValidUntil()->format('Y-m-d'));
        $this->assertEquals('B', $energiepass->getEnergyEfficiencyFactorClass());
        $this->assertEquals(0.96, $energiepass->getEnergyEfficiencyFactorValue());
        $this->assertEquals('B', $energiepass->getThermalHeatRequirementClass());
        $this->assertEquals(44, $energiepass->getThermalHeatRequirementValue());

        $this->assertEquals(array(
            'ausricht_balkon_terrasse' => 'NORD',
            'bad'                      => array('FENSTER', 'WANNE', 'DUSCHE', 'BIDET', 'PISSOIR'),
            'boden'                    => array('PARKETT', 'STEIN', 'TEPPICH', 'MARMOR'),
            'fahrstuhl'                => 'PERSONEN',
            'heizungsart'              => 'FUSSBODEN',
            'sicherheitstechnik'       => array('ALARMANLAGE', 'POLIZEIRUF'),
            'kabel_sat_tv'             => 'kabel_sat_tv',
            'kamin'                    => 'kamin',
            'kueche'                   => 'OFFEN',
            'sauna'                    => 'sauna',
            'stellplatzart'            => 'TIEFGARAGE',
            'befeuerung'               => 'SOLAR',
        ), $objekt->getEquipment());

        $contact = $objekt->getContact();
        $this->assertInstanceOf('\Justimmo\Model\Employee', $contact);
        $this->assertEquals(100123, $contact->getId());
        $this->assertEquals('Alexander', $contact->getFirstName());
        $this->assertEquals('Diem', $contact->getLastName());
        $this->assertEquals('+43 1 888 74 72', $contact->getMobile());
        $this->assertEquals('+43 676 123 45 67', $contact->getPhone());
        $this->assertEquals('+43 767 765 43 21', $contact->getFax());
        $this->assertEquals('a.diem@bgcc.at', $contact->getEmail());
        $this->assertEquals(1, count($contact->getAttachments()));

        $this->assertEquals('Freitext 1', $objekt->getFreetext1());
        $this->assertNull($objekt->getFreetext2());
        $this->assertNull($objekt->getFreetext3());
        $this->assertEquals('Im schönen Grünen', $objekt->getLocality());

        $this->assertEquals(2, count($objekt->getCategories()));
        $this->assertEquals(array(
            2057 => 'Referenzobjekte',
            3449 => 'Topobjekte',
        ), $objekt->getCategories());

        $this->assertEquals('November 2015', $objekt->getAvailableFrom());

        $this->assertEquals('aktiv', $objekt->getStatus());
        $this->assertEquals(5, $objekt->getStatusId());
    }
}
