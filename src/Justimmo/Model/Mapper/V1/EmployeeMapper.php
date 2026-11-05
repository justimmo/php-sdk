<?php
namespace Justimmo\Model\Mapper\V1;

class EmployeeMapper extends AbstractMapper
{
    /**
     * The user-defined simple fields of a contact person: fields Justimmo adds to the standard, each
     * sent with its name
     *
     * Maps the api's name to the property of Employee. UserDefinedSimpleFieldsTest compares the names
     * with SupportedParameters::USER_DEFINED_SIMPLE_FIELDS.
     */
    private const USER_DEFINED_SIMPLE_FIELD_MAPPING = [
        'immocard_id'       => [
            'property' => 'immobiliencardId',
        ],
        'immocard_firma_id' => [
            'property' => 'immobiliencardUid',
        ],
    ];

    /**
     * The standard fields of a contact person: each arrives as an element of its own
     *
     * Maps the api's name to the property of Employee and its type.
     */
    private const STANDARD_FIELD_MAPPING = [
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

    /**
     * Both lists in one, built once when the class loads
     */
    private const MAPPING = self::USER_DEFINED_SIMPLE_FIELD_MAPPING + self::STANDARD_FIELD_MAPPING;

    protected function getMapping()
    {
        return self::MAPPING;
    }

    protected function getFilterMapping()
    {
        return [];
    }

}
