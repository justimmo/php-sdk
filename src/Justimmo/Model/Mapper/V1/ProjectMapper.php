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
        );
    }
}
