<?php
namespace Justimmo\Model\Mapper\V1;

class EmployeeMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return array(
            'id'        => array(
                'type' => 'int',
            ),
            'vorname'   => array(
                'property' => 'firstName',
            ),
            'nachname'  => array(
                'property' => 'lastName',
            ),
            'handy'     => array(
                'property' => 'mobile',
            ),
            'tel'       => array(
                'property' => 'phone',
            ),
            'kategorie' => array(
                'property' => 'category',
            ),
            'titel'     => array(
                'property' => 'title',
            ),
        );
    }

    protected function getFilterMapping()
    {
        return array();
    }


}
