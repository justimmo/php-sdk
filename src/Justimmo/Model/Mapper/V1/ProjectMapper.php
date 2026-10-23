<?php

namespace Justimmo\Model\Mapper\V1;

class ProjectMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return [
            'id'                   => [
                'type' => 'int',
            ],
            'nummer'               => [
                'property' => 'projectNumber',
            ],
            'titel'                => [
                'property' => 'title',
            ],
            'beschreibung'         => [
                'property' => 'description',
            ],
            'land'                 => [
                'property' => 'country',
            ],
            'bundesland'           => [
                'property' => 'federalState',
            ],
            'ort'                  => [
                'property' => 'place',
            ],
            'plz'                  => [
                'property' => 'zipCode',
            ],
            'strasse'              => [
                'property' => 'street',
            ],
            'hausnummer'           => [
                'property' => 'houseNumber',
            ],
            'naehe'                => [
                'property' => 'proximity',
            ],
            'anzahl_etagen'        => [
                'property' => 'tierCount',
                'type'     => 'int',
            ],
            'anzahl_dachgeschosse' => [
                'property' => 'atticCount',
                'type'     => 'int',
            ],
            'bauart_id'            => [
                'property' => 'styleOfBuildingId',
                'type'     => 'int',
            ],
            'baujahr'              => [
                'property' => 'yearOfConstruction',
            ],
            'laermpegel'           => [
                'property' => 'noiseLevel'
            ],
            'beziehbar'            => [
                'property' => 'availability'
            ],
            'ea_gueltig_bis'       => [
                'property' => 'epcValidUntil',
                'type'     => 'datetime',
            ],
            'ea_hwb'               => [
                'property' => 'epcHeatingDemand'
            ],
            'ea_hwb_klasse'        => [
                'property' => 'epcHeatingDemandClass'
            ],
            'ea_fgee'              => [
                'property' => 'epcEnergyEfficiencyFactor'
            ],
            'ea_fgee_klasse'       => [
                'property' => 'epcEnergyEfficiencyFactorClass'
            ],
            'zustand'              => [
                'property' => 'condition',
            ],
            'haus_zustand'         => [
                'property' => 'houseCondition',
            ],
            'lagebewertung'        => [
                'property' => 'areaAssessment',
            ],
            'zustandsbewertung'    => [
                'property' => 'propertyAssessment',
            ],
            'nutzungsart'          => [
                'property' => 'occupancy',
            ],
            'dreizeiler'           => [
                'property' => 'teaser',
            ],
            'status'               => [
                'property' => 'projectState',
            ],
            'sonstige_angaben'     => [
                'property' => 'miscellaneous',
            ],
            'lage'                 => [
                'property' => 'locality',
            ],
            'freitext_1'           => [
                'property' => 'freetext1',
            ],
            'freitext_2'           => [
                'property' => 'freetext2',
            ],
            'freitext_3'           => [
                'property' => 'freetext3',
            ],
            'freitext_4'           => [
                'property' => 'freetext4',
            ],
            'referenz'             => [
                'property' => 'isReference',
                'type'     => 'boolean',
            ],
            'verkaufsstart'        => [
                'property' => 'saleStart',
                'type'     => 'datetime',
            ],
            'fertigstellung'       => [
                'property' => 'completionDate',
                'type'     => 'datetime',
            ],
            'erstellt_am'          => [
                'property' => 'createdAt',
                'type'     => 'datetime',
            ],
        ];
    }

    protected function getFilterMapping()
    {
        return [
            'RealtyCategory'        => 'tag_name',
            'Tag'                   => 'tag_name',
            'ProjectTag'            => 'project_tag_name',
            'Keyword'               => 'stichwort',
            'FederalStateId'        => 'bundesland_id',
            'ProjectState'          => 'projekt_status',
            'IsReference'           => 'referenz',
            'CompletionDate'        => 'fertigstellung',
            'SaleStart'             => 'verkaufsstart',

            // the fifteen the api documents and the SDK could not send. Thirteen reuse the names
            // RealtyMapper already gives them, so one method means one thing on both queries
            'Area'                  => 'flaeche',
            'SurfaceArea'           => 'grundflaeche',
            'Buy'                   => 'kauf',
            'Rent'                  => 'miete',
            'FloorArea'             => 'nutzflaeche',
            'StatusId'              => 'objekt_status_id',
            'RealtyTypeId'          => 'objektart_id',
            'PropertyNumber'        => 'objektnummer',
            'OwnerId'               => 'owner_id',
            'ZipCode'               => 'plz',
            'Price'                 => 'preis',
            'ProjectPropertyNumber' => 'projekt_objektnummer',
            'SubRealtyTypeId'       => 'subobjektart_id',
            'LivingArea'            => 'wohnflaeche',
            'Rooms'                 => 'zimmer',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * projekt sorts by created_at and updated_at, and neither of them is a filter, so both have to
     * be declared here. Everything else is inherited from the filter mapping, as it was before the
     * two tables were split.
     */
    protected function getOrderMapping(): array
    {
        return [
            'CreatedAt' => 'created_at',
            'UpdatedAt' => 'updated_at',
        ] + $this->getFilterMapping();
    }
}
