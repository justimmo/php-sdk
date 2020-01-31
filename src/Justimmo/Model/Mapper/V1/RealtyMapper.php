<?php

namespace Justimmo\Model\Mapper\V1;

use Justimmo\Model\Realty;

class RealtyMapper extends AbstractMapper
{
    /**
     * @var array String orientation value to realty const mapping.
     */
    private static $ORIENTATION_MAPPING = array(
        'N'   => Realty::ORIENTATION_NORTH,
        'NO'  => Realty::ORIENTATION_NORTH_EAST,
        'O'   => Realty::ORIENTATION_EAST,
        'SO'  => Realty::ORIENTATION_SOUTH_EAST,
        'S'   => Realty::ORIENTATION_SOUTH,
        'SW'  => Realty::ORIENTATION_SOUTH_WEST,
        'W'   => Realty::ORIENTATION_WEST,
        'NW'  => Realty::ORIENTATION_NORTH_WEST,
        'NS'  => Realty::ORIENTATION_NORTH_SOUTH,
        'OW'  => Realty::ORIENTATION_EAST_WEST,
        'NOW' => Realty::ORIENTATION_NORTH_EAST_WEST,
        'NOS' => Realty::ORIENTATION_NORTH_EAST_SOUTH,
        'SOW' => Realty::ORIENTATION_SOUTH_EAST_WEST,
        'SWN' => Realty::ORIENTATION_SOUTH_WEST_NORTH,
    );

    protected function getMapping()
    {
        return array(
            'id'                               => array(
                'type' => 'int',
            ),
            'objektnummer'                     => array(
                'property' => 'propertyNumber',
            ),
            'sonstige_angaben'                 => array(
                'property' => 'otherInformation',
            ),
            'titel'                            => array(
                'property' => 'title',
            ),
            'dreizeiler'                       => array(
                'property' => 'teaser',
            ),
            'naehe'                            => array(
                'property' => 'proximity',
            ),
            'objektbeschreibung'               => array(
                'property' => 'description',
            ),
            'etage'                            => array(
                'property' => 'tier',
            ),
            'stiege'                           => array(
                'property' => 'stair',
            ),
            'tuernummer'                       => array(
                'property' => 'doorNumber',
            ),
            'plz'                              => array(
                'property' => 'zipCode',
            ),
            'ort'                              => array(
                'property' => 'place',
            ),
            'kaufpreis'                        => array(
                'property' => 'purchasePrice',
                'type'     => 'double',
            ),
            'kaufpreisnetto'                   => array(
                'property' => 'purchasePriceNet',
                'type'     => 'double',
            ),
            'gesamtmiete'                      => array(
                'property' => 'totalRent',
                'type'     => 'double',
            ),
            'kaufpreis_pro_qm'                 => array(
                'property' => 'purchasePricePerSqm',
                'type'     => 'double',
            ),
            'mietpreis_pro_qm'                 => array(
                'property' => 'rentPerSqm',
                'type'     => 'double',
            ),
            'betriebskosten_pro_qm'            => array(
                'property' => 'operatingCostsPerSqm',
                'type'     => 'double',
            ),
            'freitext_preis'                   => array(
                'property' => 'costsExplanation',
            ),
            'nutzflaeche'                      => array(
                'property' => 'floorArea',
                'type'     => 'double',
            ),
            'teilbar_ab'                       => array(
                'property' => 'floorAreaFrom',
                'type'     => 'double',
            ),
            'grundflaeche'                     => array(
                'property' => 'surfaceArea',
                'type'     => 'double',
            ),
            'verbaubare_flaeche'               => array(
                'property' => 'buildableArea',
                'type'     => 'double',
            ),
            'wohnflaeche'                      => array(
                'property' => 'livingArea',
                'type'     => 'double',
            ),
            'gesamtflaeche'                    => array(
                'property' => 'totalArea',
                'type'     => 'double',
            ),
            'projekt_id'                       => array(
                'property' => 'projectId',
                'type'     => 'int',
            ),
            'regionaler_zusatz'                => array(
                'property' => 'regionalAddition',
            ),
            'gemarkung'                        => array(
                'property' => 'district',
            ),
            'flur'                             => array(
                'property' => 'hallway',
            ),
            'flurstueck'                       => array(
                'property' => 'landParcel',
            ),
            'bundesland'                       => array(
                'property' => 'federalState',
            ),
            'land'                             => array(
                'property' => 'country',
            ),
            'strasse'                          => array(
                'property' => 'street',
            ),
            'hausnummer'                       => array(
                'property' => 'houseNumber',
            ),
            'anzahl_etagen'                    => array(
                'property' => 'tierCount',
                'type'     => 'int',
            ),
            'kaltmiete'                        => array(
                'property' => 'totalRentWithoutHeating',
                'type'     => 'double',
            ),
            'nettokaltmiete'                   => array(
                'property' => 'netRent',
                'type'     => 'double',
            ),
            'nebenkosten'                      => array(
                'property' => 'additionalCharges',
                'type'     => 'double',
            ),
            'heizkosten'                       => array(
                'property' => 'heatingCosts',
                'type'     => 'double',
            ),
            'wohnbaufoerderung'                => array(
                'property' => 'buildingSubsidies',
                'type'     => 'double',
            ),
            'rendite'                          => array(
                'property' => 'yield',
                'type'     => 'double',
            ),
            'nettoertrag_jaehrlich'            => array(
                'property' => 'netEarningYearly',
                'type'     => 'double',
            ),
            'nettoertrag_monatlich'            => array(
                'property' => 'netEarningMonthly',
                'type'     => 'double',
            ),
            'gesamtmiete_ust'                  => array(
                'property' => 'totalRentVat',
                'type'     => 'double',
            ),
            'grunderwerbssteuer'               => array(
                'property' => 'transferTax',
                'type'     => 'double',
            ),
            'grundbucheintragung'              => array(
                'property' => 'landRegistration',
                'type'     => 'double',
            ),
            'vertragserrichtungsgebuehr'       => array(
                'property' => 'contractEstablishmentCosts',
                'type'     => 'string',
            ),
            'kaution'                          => array(
                'property' => 'surety',
                'type'     => 'double',
            ),
            'kaution_text'                     => array(
                'property' => 'surety_text',
                'type'     => 'string',
            ),
            'abstand'                          => array(
                'property' => 'compensation',
                'type'     => 'double',
            ),
            'anzahl_zimmer'                    => array(
                'property' => 'roomCount',
                'type'     => 'double',
            ),
            'anzahl_badezimmer'                => array(
                'property' => 'bathroomCount',
                'type'     => 'int',
            ),
            'anzahl_sep_wc'                    => array(
                'property' => 'toiletRoomCount',
                'type'     => 'int',
            ),
            'anzahl_balkon_terrassen'          => array(
                'property' => 'balconyTerraceCount',
                'type'     => 'iny',
            ),
            'balkon_terrasse_flaeche'          => array(
                'property' => 'balconyTerraceArea',
                'type'     => 'double',
            ),
            'anzahl_balkone'                   => array(
                'property' => 'balconyCount',
                'type'     => 'int',
            ),
            'anzahl_balkons'                   => array(
                'property' => 'balconyCount',
                'type'     => 'int',
            ),
            'anzahl_terrassen'                 => array(
                'property' => 'terraceCount',
                'type'     => 'int',
            ),
            'anzahl_garten'                    => array(
                'property' => 'gardenCount',
                'type'     => 'int',
            ),
            'gartenflaeche'                    => array(
                'property' => 'gardenArea',
                'type'     => 'double',
            ),
            'anzahl_keller'                    => array(
                'property' => 'cellarCount',
                'type'     => 'int',
            ),
            'kellerflaeche'                    => array(
                'property' => 'cellarArea',
                'type'     => 'double',
            ),
            'bueroflaeche'                     => array(
                'property' => 'officeArea',
                'type'     => 'double',
            ),
            'lagerflaeche'                     => array(
                'property' => 'storageArea',
                'type'     => 'double',
            ),
            'anzahl_loggias'                   => array(
                'property' => 'loggiaCount',
                'type'     => 'int',
            ),
            'loggias_flaeche'                  => array(
                'property' => 'loggiaArea',
                'type'     => 'double',
            ),
            'balkons_flaeche'                  => array(
                'property' => 'balconyArea',
                'type'     => 'double',
            ),
            'terrassen_flaeche'                => array(
                'property' => 'terraceArea',
                'type'     => 'double',
            ),
            'anzahl_garagen'                   => array(
                'property' => 'garageCount',
                'type'     => 'int',
            ),
            'garagen_flaeche'                  => array(
                'property' => 'garageArea',
                'type'     => 'double',
            ),
            'anzahl_stellplaetze'              => array(
                'property' => 'parkingCount',
                'type'     => 'int',
            ),
            'stellplatz_flaeche'               => array(
                'property' => 'parkingArea',
                'type'     => 'double',
            ),
            'anzahl_abstellraum'               => array(
                'property' => 'storeRoomCount',
                'type'     => 'int',
            ),
            'raumhoehe'                        => array(
                'property' => 'ceilingHeight',
                'type'     => 'double',
            ),
            'epass_hwbwert'                    => array(
                'property' => 'thermalHeatRequirementValue',
                'type'     => 'double',
            ),
            'epass_hwbklasse'                  => array(
                'property' => 'thermalHeatRequirementClass',
                'type'     => 'string',
            ),
            'epass_fgeewert'                   => array(
                'property' => 'energyEfficiencyFactorValue',
                'type'     => 'double',
            ),
            'epass_fgeeklasse'                 => array(
                'property' => 'energyEfficiencyFactorClass',
                'type'     => 'string',
            ),
            'justimmo_freitext1'               => array(
                'property' => 'freetext1',
            ),
            'justimmo_freitext2'               => array(
                'property' => 'freetext2',
            ),
            'justimmo_freitext3'               => array(
                'property' => 'freetext3',
            ),
            'justimmo_freitext4'               => array(
                'property' => 'freetext4',
            ),
            'lage'                             => array(
                'property' => 'locality',
            ),
            'aussen_courtage'                  => array(
                'property' => 'commission',
            ),
            'status_id'                        => array(
                'type' => 'int',
            ),
            'objektart_id'                     => array(
                'property' => 'realtyTypeId',
                'type'     => 'int',
            ),
            'objektart_name'                   => array(
                'property' => 'realtyTypeName',
                'type'     => 'string',
            ),
            'sub_objektart_id'                 => array(
                'property' => 'subRealtyTypeId',
                'type'     => 'int',
            ),
            'sub_objektart_name'               => array(
                'property' => 'subRealtyTypeName',
                'type'     => 'string',
            ),
            'bauart_id'                        => array(
                'property' => 'styleOfBuildingId',
                'type'     => 'int',
            ),
            'vermittelt_am'                    => array(
                'property' => 'procuredAt',
                'type'     => 'datetime',
            ),
            'erstellt_am'                      => array(
                'property' => 'createdAt',
                'type'     => 'datetime',
            ),
            'aktualisiert_am'                  => array(
                'property' => 'updatedAt',
                'type'     => 'datetime',
            ),
            'geokoordinaten_laengengrad_exakt' => array(
                'property' => 'longitudePrecise',
                'type'     => 'double',
            ),
            'geokoordinaten_breitengrad_exakt' => array(
                'property' => 'latitudePrecise',
                'type'     => 'double',
            ),
            'ungenaue_verortung_laengengrad' => array(
                'property' => 'longitudeFuzzy',
                'type'     => 'double',
            ),
            'ungenaue_verortung_breitengrad' => array(
                'property' => 'latitudeFuzzy',
                'type'     => 'double',
            ),
            'ungenaue_verortung_radius' => array(
                'property' => 'radiusFuzzy',
                'type'     => 'int',
            ),
            'ausrichtung'                      => array(
                'property' => 'orientation',
                'type'     => 'string',
            ),
            'parent_id'                        => array(
                'property' => 'parentId',
                'type'     => 'int',
            ),
            'realty_system_type'               => array(
                'property' => 'realtySystemType',
                'type'     => 'string',
            ),
            'ji_anzeige_in_suchergebnis'       => array(
                'property' => 'showInSearch',
                'type'     => 'boolean',
            ),
            'ji_is_reference'                  => array(
                'property' => 'isReference',
                'type'     => 'boolean',
            ),
            'monatlichekostenbrutto'           => array(
                'property' => 'monthlyCosts',
                'type'     => 'double',
            ),
            'finanzierungsbeitrag'             => array(
                'property' => 'financialContribution',
                'type'     => 'double',
            ),
            'erschliessungskosten'             => array(
                'property' => 'infrastructureProvision',
                'type'     => 'double',
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
            'Price'             => 'preis',
            'RealtyTypeId'      => 'objektart_id',
            'SubRealtyTypeId'   => 'subobjektart_id',
            'StyleOfBuildingId' => 'bauart_id',
            'RealtyCategory'    => 'tag_name',
            'Tag'               => 'tag_name',
            'ZipCode'           => 'plz',
            'Rooms'             => 'zimmer',
            'PropertyNumber'    => 'objektnummer',
            'Area'              => 'sort_flaeche',
            'LivingArea'        => 'wohnflaeche',
            'FloorArea'         => 'nutzflaeche',
            'SurfaceArea'       => 'grundflaeche',
            'Keyword'           => 'stichwort',
            'FederalStateId'    => 'bundesland_id',
            'StatusId'          => 'objekt_status_id',
            'Rent'              => 'miete',
            'Buy'               => 'kauf',
            'RealtySystemType'  => 'realty_type',
            'ParentId'          => 'parent_id',
            'RentPerSqm'        => 'price_per_m2',
            'CreatedAt'         => 'created_at',
            'UpdatedAt'         => 'aktualisiert_am',
            'Condition'         => 'zustand_id',
            'GardenCount'       => 'anzahl_garten',
            'BalconyCount'      => 'anzahl_balkons',
            'LoggiaCount'       => 'anzahl_loggias',
            'TerraceCount'      => 'anzahl_terrassen',
            'CellarCount'       => 'anzahl_keller',
            'GarageCount'       => 'anzahl_garagen',
            'ParkingCount'      => 'anzahl_stellplaetze',
            'ToiletRoomCount'   => 'anzahl_sep_wc',
            'BathroomCount'     => 'anzahl_badezimmer',
            'StoreRoomCount'    => 'anzahl_abstellraum',
            'Equipment'         => 'objekt_ausstattung_list',
            'DisabilityAccess'  => 'barrierefrei',
        );
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param Realty            $realty
     */
    public function setOrientation(\SimpleXMLElement $xml, Realty $realty)
    {
        $value = (string)$xml;

        if (array_key_exists($value, self::$ORIENTATION_MAPPING)) {
            $orientation = self::$ORIENTATION_MAPPING[$value];
        } else {
            $orientation = null;
        }

        $realty->setOrientation($orientation);
    }
}
