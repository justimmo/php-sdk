<?php
namespace Justimmo\Model\Mapper\V1;

class RealtyMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return array(
            'id'                         => array(
                'type' => 'int',
            ),
            'objektnummer'               => array(
                'property' => 'propertyNumber',
            ),
            'titel'                      => array(
                'property' => 'title',
            ),
            'dreizeiler'                 => array(
                'property' => 'teaser',
            ),
            'naehe'                      => array(
                'property' => 'proximity',
            ),
            'objektbeschreibung'         => array(
                'property' => 'description',
            ),
            'etage'                      => array(
                'property' => 'tier',
            ),
            'tuernummer'                 => array(
                'property' => 'doorNumber',
            ),
            'plz'                        => array(
                'property' => 'zipCode',
            ),
            'ort'                        => array(
                'property' => 'place',
            ),
            'kaufpreis'                  => array(
                'property' => 'purchasePrice',
                'type'     => 'double',
            ),
            'gesamtmiete'                => array(
                'property' => 'totalRent',
                'type'     => 'double',
            ),
            'nutzflaeche'                => array(
                'property' => 'floorArea',
                'type'     => 'double',
            ),
            'grundflaeche'               => array(
                'property' => 'surfaceArea',
                'type'     => 'double',
            ),
            'wohnflaeche'                => array(
                'property' => 'livingArea',
                'type'     => 'double',
            ),
            'gesamtflaeche'              => array(
                'property' => 'totalArea',
                'type'     => 'double',
            ),
            'projekt_id'                 => array(
                'property' => 'projectId',
                'type'     => 'int',
            ),
            'regionaler_zusatz'          => array(
                'property' => 'regionalAddition',
            ),
            'gemarkung'                  => array(
                'property' => 'district',
            ),
            'flur'                       => array(
                'property' => 'hallway',
            ),
            'flurstueck'                 => array(
                'property' => 'landParcel',
            ),
            'bundesland'                 => array(
                'property' => 'federalState',
            ),
            'land'                       => array(
                'property' => 'country',
            ),
            'strasse'                    => array(
                'property' => 'street',
            ),
            'hausnummer'                 => array(
                'property' => 'houseNumber',
            ),
            'anzahl_etagen'              => array(
                'property' => 'tierCount',
                'type'     => 'int',
            ),
            'nettokaltmiete'             => array(
                'property' => 'netRent',
                'type'     => 'double',
            ),
            'nebenkosten'                => array(
                'property' => 'additionalCharges',
                'type'     => 'double',
            ),
            'heizkosten'                 => array(
                'property' => 'heatingCosts',
                'type'     => 'double',
            ),
            'wohnbaufoerderung'          => array(
                'property' => 'buildingSubsidies',
                'type'     => 'double',
            ),
            'rendite'                    => array(
                'property' => 'yield',
                'type'     => 'double',
            ),
            'nettoertrag_jaehrlich'      => array(
                'property' => 'netEarningYearly',
                'type'     => 'double',
            ),
            'nettoertrag_monatlich'      => array(
                'property' => 'netEarningMonthly',
                'type'     => 'double',
            ),
            'gesamtmiete_ust'            => array(
                'property' => 'totalRentVat',
                'type'     => 'double',
            ),
            'grunderwerbssteuer'         => array(
                'property' => 'transferTax',
                'type'     => 'double',
            ),
            'grundbucheintragung'        => array(
                'property' => 'landRegistration',
                'type'     => 'double',
            ),
            'vertragserrichtungsgebuehr' => array(
                'property' => 'contractEstablishmentCosts',
                'type'     => 'double',
            ),
            'kaution'                    => array(
                'property' => 'surety',
                'type'     => 'double',
            ),
            'abstand'                    => array(
                'property' => 'compensation',
                'type'     => 'double',
            ),
            'anzahl_zimmer'              => array(
                'property' => 'roomCount',
                'type'     => 'int',
            ),
            'anzahl_badezimmer'          => array(
                'property' => 'bathroomCount',
                'type'     => 'int',
            ),
            'anzahl_sep_wc'              => array(
                'property' => 'toiletRoomCount',
                'type'     => 'int',
            ),
            'anzahl_balkon_terrassen'    => array(
                'property' => 'balconyTerraceCount',
                'type'     => 'iny',
            ),
            'balkon_terrasse_flaeche'    => array(
                'property' => 'balconyTerraceArea',
                'type'     => 'double',
            ),
            'anzahl_balkone'             => array(
                'property' => 'balconyCount',
                'type'     => 'int',
            ),
            'anzahl_balkons'             => array(
                'property' => 'balconyCount',
                'type'     => 'int',
            ),
            'anzahl_terrassen'           => array(
                'property' => 'terraceCount',
                'type'     => 'int',
            ),
            'gartenflaeche'              => array(
                'property' => 'gardenArea',
                'type'     => 'double',
            ),
            'kellerflaeche'              => array(
                'property' => 'cellarArea',
                'type'     => 'double',
            ),
            'bueroflaeche'               => array(
                'property' => 'officeArea',
                'type'     => 'double',
            ),
            'lagerflaeche'               => array(
                'property' => 'storageArea',
                'type'     => 'double',
            ),
            'anzahl_loggias'             => array(
                'property' => 'loggiaCount',
                'type'     => 'int',
            ),
            'loggias_flaeche'            => array(
                'property' => 'loggiaArea',
                'type'     => 'double',
            ),
            'balkons_flaeche'            => array(
                'property' => 'balconyArea',
                'type'     => 'double',
            ),
            'terrassen_flaeche'          => array(
                'property' => 'terraceArea',
                'type'     => 'double',
            ),
            'anzahl_garagen'             => array(
                'property' => 'garageCount',
                'type'     => 'int',
            ),
            'anzahl_abstellraum'         => array(
                'property' => 'storeRoomCount',
                'type'     => 'int',
            ),
            'epass_hwbwert'              => array(
                'property' => 'thermalHeatRequirementValue',
            ),
            'epass_hwbklasse'            => array(
                'property' => 'thermalHeatRequirementClass',
            ),
            'epass_fgeewert'             => array(
                'property' => 'energyEfficiencyFactorValue',
            ),
            'epass_fgeeklasse'           => array(
                'property' => 'energyEfficiencyFactorClass',
            ),
            'justimmo_freitext1'         => array(
                'property' => 'freetext1',
            ),
            'justimmo_freitext2'         => array(
                'property' => 'freetext2',
            ),
            'justimmo_freitext3'         => array(
                'property' => 'freetext3',
            ),
        );
    }

    /**
     * get mapping for filter
     *
     * @return array
     */
    protected function getFilterMapping()
    {
        return array(
            'Price'          => 'preis',
            'RealtyTypeId'   => 'objektart_id',
            'RealtyCategory' => 'tag_name',
            'Tag'            => 'tag_name',
            'ZipCode'        => 'plz',
            'Rooms'          => 'zimmer',
            'PropertyNumber' => 'objektnummer',
            'LivingArea'     => 'wohnflaeche',
            'FloorArea'      => 'nutzflaeche',
            'SurfaceArea'    => 'grundflaeche',
            'Keyword'        => 'stichwort',
            'FederalStateId' => 'bundesland_id',
            'Rent'           => 'miete',
            'Buy'            => 'kauf',
        );
    }
}
