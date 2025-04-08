<?php

namespace Justimmo\Model\Mapper\V1;

use Justimmo\Model\Realty;

class RealtyMapper extends AbstractMapper
{
    /**
     * @var array String orientation value to realty const mapping.
     */
    private static $ORIENTATION_MAPPING = [
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
    ];

    protected function getMapping()
    {
        return [
            'id'                               => [
                'type' => 'int',
            ],
            'objektnummer'                     => [
                'property' => 'propertyNumber',
            ],
            'sonstige_angaben'                 => [
                'property' => 'otherInformation',
            ],
            'titel'                            => [
                'property' => 'title',
            ],
            'dreizeiler'                       => [
                'property' => 'teaser',
            ],
            'naehe'                            => [
                'property' => 'proximity',
            ],
            'objektbeschreibung'               => [
                'property' => 'description',
            ],
            'etage'                            => [
                'property' => 'tier',
            ],
            'stiege'                           => [
                'property' => 'stair',
            ],
            'tuernummer'                       => [
                'property' => 'doorNumber',
            ],
            'plz'                              => [
                'property' => 'zipCode',
            ],
            'ort'                              => [
                'property' => 'place',
            ],
            'kaufpreis'                        => [
                'property' => 'purchasePrice',
                'type'     => 'double',
            ],
            'kaufpreisnetto'                   => [
                'property' => 'purchasePriceNet',
                'type'     => 'double',
            ],
            'gesamtmiete'                      => [
                'property' => 'totalRent',
                'type'     => 'double',
            ],
            'kaufpreis_pro_qm'                 => [
                'property' => 'purchasePricePerSqm',
                'type'     => 'double',
            ],
            'mietpreis_pro_qm'                 => [
                'property' => 'rentPerSqm',
                'type'     => 'double',
            ],
            'betriebskosten_pro_qm'            => [
                'property' => 'operatingCostsPerSqm',
                'type'     => 'double',
            ],
            'freitext_preis'                   => [
                'property' => 'costsExplanation',
            ],
            'nutzflaeche'                      => [
                'property' => 'floorArea',
                'type'     => 'double',
            ],
            'teilbar_ab'                       => [
                'property' => 'floorAreaFrom',
                'type'     => 'double',
            ],
            'grundflaeche'                     => [
                'property' => 'surfaceArea',
                'type'     => 'double',
            ],
            'verbaubare_flaeche'               => [
                'property' => 'buildableArea',
                'type'     => 'double',
            ],
            'verkaufsflaeche'                  => [
                'property' => 'salesArea',
                'type'     => 'double',
            ],
            'befristete_flaeche'               => [
                'property' => 'shortTermArea',
                'type'     => 'double',
            ],
            'gewichtete_flaeche'               => [
                'property' => 'weightedArea',
                'type'     => 'double',
            ],
            'rohdachboden_flaeche'             => [
                'property' => 'undevelopedAtticArea',
                'type'     => 'double',
            ],
            'wohnflaeche'                      => [
                'property' => 'livingArea',
                'type'     => 'double',
            ],
            'gesamtflaeche'                    => [
                'property' => 'totalArea',
                'type'     => 'double',
            ],
            'projekt_id'                       => [
                'property' => 'projectId',
                'type'     => 'int',
            ],
            'regionaler_zusatz'                => [
                'property' => 'regionalAddition',
            ],
            'gemarkung'                        => [
                'property' => 'district',
            ],
            'flur'                             => [
                'property' => 'hallway',
            ],
            'flurstueck'                       => [
                'property' => 'landParcel',
            ],
            'bundesland'                       => [
                'property' => 'federalState',
            ],
            'land'                             => [
                'property' => 'country',
            ],
            'strasse'                          => [
                'property' => 'street',
            ],
            'hausnummer'                       => [
                'property' => 'houseNumber',
            ],
            'anzahl_etagen'                    => [
                'property' => 'tierCount',
                'type'     => 'int',
            ],
            'kaltmiete'                        => [
                'property' => 'totalRentWithoutHeating',
                'type'     => 'double',
            ],
            'nettokaltmiete'                   => [
                'property' => 'netRent',
                'type'     => 'double',
            ],
            'nebenkosten'                      => [
                'property' => 'additionalCharges',
                'type'     => 'double',
            ],
            'heizkosten'                       => [
                'property' => 'heatingCosts',
                'type'     => 'double',
            ],
            'wohnbaufoerderung'                => [
                'property' => 'buildingSubsidies',
                'type'     => 'double',
            ],
            'rendite'                          => [
                'property' => 'yield',
                'type'     => 'double',
            ],
            'nettoertrag_jaehrlich'            => [
                'property' => 'netEarningYearly',
                'type'     => 'double',
            ],
            'nettoertrag_monatlich'            => [
                'property' => 'netEarningMonthly',
                'type'     => 'double',
            ],
            'gesamtmiete_ust'                  => [
                'property' => 'totalRentVat',
                'type'     => 'double',
            ],
            'grunderwerbssteuer'               => [
                'property' => 'transferTax',
                'type'     => 'double',
            ],
            'grundbucheintragung'              => [
                'property' => 'landRegistration',
                'type'     => 'double',
            ],
            'vertragserrichtungsgebuehr'       => [
                'property' => 'contractEstablishmentCosts',
                'type'     => 'string',
            ],
            'kaution'                          => [
                'property' => 'surety',
                'type'     => 'double',
            ],
            'kaution_text'                     => [
                'property' => 'surety_text',
                'type'     => 'string',
            ],
            'abstand'                          => [
                'property' => 'compensation',
                'type'     => 'double',
            ],
            'anzahl_zimmer'                    => [
                'property' => 'roomCount',
                'type'     => 'double',
            ],
            'anzahl_badezimmer'                => [
                'property' => 'bathroomCount',
                'type'     => 'int',
            ],
            'anzahl_sep_wc'                    => [
                'property' => 'toiletRoomCount',
                'type'     => 'int',
            ],
            'anzahl_balkon_terrassen'          => [
                'property' => 'balconyTerraceCount',
                'type'     => 'iny',
            ],
            'balkon_terrasse_flaeche'          => [
                'property' => 'balconyTerraceArea',
                'type'     => 'double',
            ],
            'anzahl_balkone'                   => [
                'property' => 'balconyCount',
                'type'     => 'int',
            ],
            'anzahl_balkons'                   => [
                'property' => 'balconyCount',
                'type'     => 'int',
            ],
            'anzahl_terrassen'                 => [
                'property' => 'terraceCount',
                'type'     => 'int',
            ],
            'anzahl_garten'                    => [
                'property' => 'gardenCount',
                'type'     => 'int',
            ],
            'gartenflaeche'                    => [
                'property' => 'gardenArea',
                'type'     => 'double',
            ],
            'anzahl_keller'                    => [
                'property' => 'cellarCount',
                'type'     => 'int',
            ],
            'kellerflaeche'                    => [
                'property' => 'cellarArea',
                'type'     => 'double',
            ],
            'bueroflaeche'                     => [
                'property' => 'officeArea',
                'type'     => 'double',
            ],
            'lagerflaeche'                     => [
                'property' => 'storageArea',
                'type'     => 'double',
            ],
            'anzahl_loggias'                   => [
                'property' => 'loggiaCount',
                'type'     => 'int',
            ],
            'loggias_flaeche'                  => [
                'property' => 'loggiaArea',
                'type'     => 'double',
            ],
            'balkons_flaeche'                  => [
                'property' => 'balconyArea',
                'type'     => 'double',
            ],
            'terrassen_flaeche'                => [
                'property' => 'terraceArea',
                'type'     => 'double',
            ],
            'anzahl_garagen'                   => [
                'property' => 'garageCount',
                'type'     => 'int',
            ],
            'garagen_flaeche'                  => [
                'property' => 'garageArea',
                'type'     => 'double',
            ],
            'anzahl_stellplaetze'              => [
                'property' => 'parkingCount',
                'type'     => 'int',
            ],
            'stellplatz_flaeche'               => [
                'property' => 'parkingArea',
                'type'     => 'double',
            ],
            'anzahl_abstellraum'               => [
                'property' => 'storeRoomCount',
                'type'     => 'int',
            ],
            'raumhoehe'                        => [
                'property' => 'ceilingHeight',
                'type'     => 'double',
            ],
            'hallenhoehe'                      => [
                'property' => 'hallHeight',
                'type'     => 'double',
            ],
            'epass_hwbwert'                    => [
                'property' => 'thermalHeatRequirementValue',
                'type'     => 'double',
            ],
            'epass_hwbklasse'                  => [
                'property' => 'thermalHeatRequirementClass',
                'type'     => 'string',
            ],
            'epass_fgeewert'                   => [
                'property' => 'energyEfficiencyFactorValue',
                'type'     => 'double',
            ],
            'epass_fgeeklasse'                 => [
                'property' => 'energyEfficiencyFactorClass',
                'type'     => 'string',
            ],
            'justimmo_freitext1'               => [
                'property' => 'freetext1',
            ],
            'justimmo_freitext2'               => [
                'property' => 'freetext2',
            ],
            'justimmo_freitext3'               => [
                'property' => 'freetext3',
            ],
            'justimmo_freitext4'               => [
                'property' => 'freetext4',
            ],
            'lage'                             => [
                'property' => 'locality',
            ],
            'aussen_courtage'                  => [
                'property' => 'commission',
            ],
            'status_id'                        => [
                'type' => 'int',
            ],
            'objektart_id'                     => [
                'property' => 'realtyTypeId',
                'type'     => 'int',
            ],
            'objektart_name'                   => [
                'property' => 'realtyTypeName',
                'type'     => 'string',
            ],
            'sub_objektart_id'                 => [
                'property' => 'subRealtyTypeId',
                'type'     => 'int',
            ],
            'sub_objektart_name'               => [
                'property' => 'subRealtyTypeName',
                'type'     => 'string',
            ],
            'bauart_id'                        => [
                'property' => 'styleOfBuildingId',
                'type'     => 'int',
            ],
            'ji_eigentumsform_id'              => [
                'property' => 'ownershipTypeId',
                'type'     => 'int',
            ],
            'vermittelt_am'                    => [
                'property' => 'procuredAt',
                'type'     => 'datetime',
            ],
            'erstellt_am'                      => [
                'property' => 'createdAt',
                'type'     => 'datetime',
            ],
            'aktualisiert_am'                  => [
                'property' => 'updatedAt',
                'type'     => 'datetime',
            ],
            'geokoordinaten_laengengrad_exakt' => [
                'property' => 'longitudePrecise',
                'type'     => 'double',
            ],
            'geokoordinaten_breitengrad_exakt' => [
                'property' => 'latitudePrecise',
                'type'     => 'double',
            ],
            'ungenaue_verortung_laengengrad'   => [
                'property' => 'longitudeFuzzy',
                'type'     => 'double',
            ],
            'ungenaue_verortung_breitengrad'   => [
                'property' => 'latitudeFuzzy',
                'type'     => 'double',
            ],
            'ungenaue_verortung_radius'        => [
                'property' => 'radiusFuzzy',
                'type'     => 'int',
            ],
            'ausrichtung'                      => [
                'property' => 'orientation',
                'type'     => 'string',
            ],
            'parent_id'                        => [
                'property' => 'parentId',
                'type'     => 'int',
            ],
            'realty_system_type'               => [
                'property' => 'realtySystemType',
                'type'     => 'string',
            ],
            'ji_anzeige_in_suchergebnis'       => [
                'property' => 'showInSearch',
                'type'     => 'boolean',
            ],
            'ji_is_reference'                  => [
                'property' => 'isReference',
                'type'     => 'boolean',
            ],
            'monatlichekostenbrutto'           => [
                'property' => 'monthlyCosts',
                'type'     => 'double',
            ],
            'finanzierungsbeitrag'             => [
                'property' => 'financialContribution',
                'type'     => 'double',
            ],
            'erschliessungskosten'             => [
                'property' => 'infrastructureProvision',
                'type'     => 'double',
            ],
            'mietkauf'                         => [
                'property' => 'rentWithPurchaseOption',
                'type'     => 'boolean',
            ],
        ];
    }

    /**
     * get mapping for filter
     *
     * @return array
     */
    protected function getFilterMapping()
    {
        return [
            'Price'                  => 'preis',
            'RealtyTypeId'           => 'objektart_id',
            'SubRealtyTypeId'        => 'subobjektart_id',
            'StyleOfBuildingId'      => 'bauart_id',
            'RealtyCategory'         => 'tag_name',
            'Tag'                    => 'tag_name',
            'ZipCode'                => 'plz',
            'Rooms'                  => 'zimmer',
            'PropertyNumber'         => 'objektnummer',
            'ProjectId'              => 'projekt_id',
            'Area'                   => 'sort_flaeche',
            'LivingArea'             => 'wohnflaeche',
            'FloorArea'              => 'nutzflaeche',
            'SurfaceArea'            => 'grundflaeche',
            'Keyword'                => 'stichwort',
            'FederalStateId'         => 'bundesland_id',
            'StatusId'               => 'objekt_status_id',
            'Rent'                   => 'miete',
            'Buy'                    => 'kauf',
            'RealtySystemType'       => 'realty_type',
            'ParentId'               => 'parent_id',
            'RentPerSqm'             => 'price_per_m2',
            'CreatedAt'              => 'created_at',
            'UpdatedAt'              => 'aktualisiert_am',
            'Condition'              => 'zustand_id',
            'GardenCount'            => 'anzahl_garten',
            'BalconyCount'           => 'anzahl_balkons',
            'LoggiaCount'            => 'anzahl_loggias',
            'TerraceCount'           => 'anzahl_terrassen',
            'CellarCount'            => 'anzahl_keller',
            'GarageCount'            => 'anzahl_garagen',
            'ParkingCount'           => 'anzahl_stellplaetze',
            'ToiletRoomCount'        => 'anzahl_sep_wc',
            'BathroomCount'          => 'anzahl_badezimmer',
            'StoreRoomCount'         => 'anzahl_abstellraum',
            'Equipment'              => 'objekt_ausstattung_list',
            'DisabilityAccess'       => 'barrierefrei',
            'RentWithPurchaseOption' => 'rent_with_purchase_option',
        ];
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param Realty            $realty
     */
    public function setOrientation(\SimpleXMLElement $xml, Realty $realty)
    {
        $value = (string) $xml;

        if (array_key_exists($value, self::$ORIENTATION_MAPPING)) {
            $orientation = self::$ORIENTATION_MAPPING[$value];
        } else {
            $orientation = null;
        }

        $realty->setOrientation($orientation);
    }
}
