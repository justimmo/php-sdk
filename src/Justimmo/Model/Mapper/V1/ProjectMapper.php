<?php

namespace Justimmo\Model\Mapper\V1;

class ProjectMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return array(
            'id'                   => array(
                'type' => 'int',
            ),
            'nummer'               => array(
                'property' => 'projectNumber',
            ),
            'titel'                => array(
                'property' => 'title',
            ),
            'beschreibung'         => array(
                'property' => 'description',
            ),
            'land'                 => array(
                'property' => 'country',
            ),
            'bundesland'           => array(
                'property' => 'federalState',
            ),
            'ort'                  => array(
                'property' => 'place',
            ),
            'plz'                  => array(
                'property' => 'zipCode',
            ),
            'strasse'              => array(
                'property' => 'street',
            ),
            'hausnummer'           => array(
                'property' => 'houseNumber',
            ),
            'naehe'                => array(
                'property' => 'proximity',
            ),
            'anzahl_etagen'        => array(
                'property' => 'tierCount',
                'type'     => 'int',
            ),
            'anzahl_dachgeschosse' => array(
                'property' => 'atticCount',
                'type'     => 'int',
            ),
            'bauart_id'            => array(
                'property' => 'styleOfBuildingId',
                'type'     => 'int',
            ),
            'baujahr'              => array(
                'property' => 'yearOfConstruction',
            ),
            'laermpegel'           => array(
                'property' => 'noiseLevel'
            ),
            'beziehbar'            => array(
                'property' => 'availability'
            ),
            'ea_gueltig_bis'       => array(
                'property' => 'epcValidUntil',
                'type'     => 'datetime',
            ),
            'ea_hwb'               => array(
                'property' => 'epcHeatingDemand'
            ),
            'ea_hwb_klasse'        => array(
                'property' => 'epcHeatingDemandClass'
            ),
            'ea_fgee'              => array(
                'property' => 'epcEnergyEfficiencyFactor'
            ),
            'ea_fgee_klasse'       => array(
                'property' => 'epcEnergyEfficiencyFactorClass'
            ),
            'zustand'              => array(
                'property' => 'condition',
            ),
            'haus_zustand'         => array(
                'property' => 'houseCondition',
            ),
            'lagebewertung'        => array(
                'property' => 'areaAssessment',
            ),
            'zustandsbewertung'    => array(
                'property' => 'propertyAssessment',
            ),
            'nutzungsart'          => array(
                'property' => 'occupancy',
            ),
            'dreizeiler'           => array(
                'property' => 'teaser',
            ),
            'status'               => array(
                'property' => 'projectState',
            ),
            'sonstige_angaben'     => array(
                'property' => 'miscellaneous',
            ),
            'lage'                 => array(
                'property' => 'locality',
            ),
            'freitext_1'           => array(
                'property' => 'freetext1',
            ),
            'freitext_2'           => array(
                'property' => 'freetext2',
            ),
            'referenz'             => array(
                'property' => 'isReference',
                'type'     => 'boolean',
            ),
            'verkaufsstart'        => array(
                'property' => 'saleStart',
                'type'     => 'datetime',
            ),
            'fertigstellung'       => array(
                'property' => 'completionDate',
                'type'     => 'datetime',
            ),
            'erstellt_am'          => array(
                'property' => 'createdAt',
                'type'     => 'datetime',
            ),
        );
    }

    protected function getFilterMapping()
    {
        return array(
            'RealtyCategory' => 'tag_name',
            'Tag'            => 'tag_name',
            'Keyword'        => 'stichwort',
            'FederalStateId' => 'bundesland_id',
            'CountryIso2'    => 'land_iso2',
            'ProjectState'   => 'projekt_status',
            'IsReference'    => 'referenz',
            'CompletionDate' => 'fertigstellung',
            'SaleStart'      => 'verkaufsstart',
        );
    }
}
