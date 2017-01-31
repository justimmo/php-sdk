<?php

namespace Justimmo\Model\Mapper\V1;

class ProjectMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return array(
            'id'           => array(
                'type' => 'int',
            ),
            'titel'        => array(
                'property' => 'title',
            ),
            'beschreibung' => array(
                'property' => 'description',
            ),
            'plz'          => array(
                'property' => 'zipCode',
            ),
            'ort'          => array(
                'property' => 'place',
            ),
            'dreizeiler'   => array(
                'property' => 'teaser',
            ),
            'strasse'   => array(
                'property' => 'street',
            ),
            'hausnummer'   => array(
                'property' => 'houseNumber',
            ),
            'status'   => array(
                'property' => 'projectState',
            ),
            'sonstige_angaben' => array(
                'property' => 'miscellaneous',
            ),
            'lage'   => array(
                'property' => 'locality',
            ),
            'freitext_1'   => array(
                'property' => 'freetext1',
            ),
            'referenz'   => array(
                'property' => 'isReference',
                'type'     => 'boolean',
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
        );
    }
}
