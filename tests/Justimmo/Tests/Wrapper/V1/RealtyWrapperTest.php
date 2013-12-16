<?php
namespace Justimmo\Tests\Wrapper\V1;

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

    public function testTransform()
    {
        $wrapper = new RealtyWrapper();
        $objekt  = $wrapper->transform($this->getFixtures('v1/realty_detail.xml'));

        $this->assertInstanceOf('\Justimmo\Model\Realty', $objekt);

        $this->assertEquals(195439, $objekt->getId());
        $this->assertEquals(51, $objekt->getProjektId());
        $this->assertEquals(34, $objekt->getObjektnummer());
        $this->assertEquals('DEMOOBJEKT! Elegantes Büro neben Bristol und Oper', $objekt->getTitel());
        $this->assertContains('ausgestattetes 1 bis 2 Personenbüro', $objekt->getObjektbeschreibung());
        $this->assertNull($objekt->getEtage());
        $this->assertNull($objekt->getTuernummer());
        $this->assertEquals(2460, $objekt->getPlz());
        $this->assertEquals('Wien', $objekt->getOrt());
        $this->assertEquals('buero_praxen', $objekt->getObjektart());

        $this->assertEquals(array(
            'WOHNEN'  => 1,
            'GEWERBE' => 1,
            'ANLAGE'  => 0,
        ), $objekt->getNutzungsart());
        $this->assertEquals(array(
            'KAUF'        => 1,
            'MIETE_PACHT' => 1,
        ), $objekt->getVermarktungsart());
        $this->assertNull($objekt->getStrasse());
        $this->assertEmpty($objekt->getFlur());
        $this->assertEmpty($objekt->getFlurstueck());
        $this->assertEmpty($objekt->getGemarkung());
        $this->assertEquals('AUT', $objekt->getLand());
        $this->assertEquals(48.0168636, $objekt->getBreitengrad());
        $this->assertEquals(16.7801812, $objekt->getLaengengrad());
        $this->assertEquals($objekt->getNutzflaeche(), 15);
        $this->assertEquals($objekt->getGrundflaeche(), 15);
        $this->assertNull($objekt->getWohnflaeche());
        $this->assertNull($objekt->getGesamtflaeche());

        $this->assertNull($objekt->getKaufpreis());
        $this->assertEquals(60000, $objekt->getGesamtmiete());
        $this->assertNull($objekt->getNettoKaltMiete());
        $this->assertEquals(50000, $objekt->getNebenkosten());
        $this->assertEquals(10000, $objekt->getHeizkosten());
        $this->assertNull($objekt->getWohnbaufoerderung());
        $this->assertNull($objekt->getRendite());
        $this->assertNull($objekt->getNettoertragMonatlich());
        $this->assertNull($objekt->getNettoertragJaehrlich());
        $this->assertNull($objekt->getGesamtMieteUst());
        $this->assertNull($objekt->getGrunderwerbssteuer());
        $this->assertNull($objekt->getGrundbucheintragung());
        $this->assertNull($objekt->getVertragserrichtungsgebuehr());
        $this->assertNull($objekt->getKaution());

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
