<?php
namespace Justimmo\Model\Mapper\V1;

class EmployeeMapper extends AbstractMapper
{
    protected function getMapping()
    {
        return [
            'id'             => [
                'type' => 'int',
            ],
            'vorname'        => [
                'property' => 'firstName',
            ],
            'nachname'       => [
                'property' => 'lastName',
            ],
            'name'           => [
                'property' => 'lastName',
            ],
            'handy'          => [
                'property' => 'mobile',
            ],
            'tel_handy'      => [
                'property' => 'mobile',
            ],
            'tel_fax'        => [
                'property' => 'fax',
            ],
            'tel'            => [
                'property' => 'phone',
            ],
            'tel_zentrale'   => [
                'property' => 'phone',
            ],
            'kategorie'      => [
                'property' => 'category',
            ],
            'titel'          => [
                'property' => 'title',
            ],
            'email_direkt'   => [
                'property' => 'email',
            ],
            'personennummer' => [
                'property' => 'number',
            ],
            'suffix'         => [
                'property' => 'suffix',
            ],
            'bio'            => [
                'property' => 'biography',
            ],
            'strasse'        => [
                'property' => 'street',
            ],
            'plz'            => [
                'property' => 'postal',
            ],
            'ort'            => [
                'property' => 'city',
            ],
            'url'            => [
                'property' => 'url',
            ],
            'anrede'         => [
                'property' => 'salutation',
            ],
            'firma'          => [
                'property' => 'company',
            ],
        ];
    }

    protected function getFilterMapping()
    {
        return [];
    }

}
