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
        );
    }

    protected function getFilterMapping()
    {
        return array();
    }


}
