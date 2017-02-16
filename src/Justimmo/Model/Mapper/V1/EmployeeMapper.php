<?php
namespace Justimmo\Model\Mapper\V1;

class EmployeeMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return array(
            'id'             => array(
                'type' => 'int',
            ),
            'vorname'        => array(
                'property' => 'firstName',
            ),
            'nachname'       => array(
                'property' => 'lastName',
            ),
            'name'           => array(
                'property' => 'lastName',
            ),
            'handy'          => array(
                'property' => 'mobile',
            ),
            'tel_handy'      => array(
                'property' => 'mobile',
            ),
            'tel_fax'        => array(
                'property' => 'fax',
            ),
            'tel'            => array(
                'property' => 'phone',
            ),
            'tel_zentrale'   => array(
                'property' => 'phone',
            ),
            'kategorie'      => array(
                'property' => 'category',
            ),
            'titel'          => array(
                'property' => 'title',
            ),
            'email_direkt'   => array(
                'property' => 'email',
            ),
            'personennummer' => array(
                'property' => 'number',
            ),
            'suffix'   => array(
                'property' => 'suffix',
            ),
            'bio'   => array(
                'property' => 'biography',
            ),
        );
    }

    protected function getFilterMapping()
    {
        return array();
    }

}
