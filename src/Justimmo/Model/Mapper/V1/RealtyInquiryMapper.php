<?php

namespace Justimmo\Model\Mapper\V1;

class RealtyInquiryMapper extends AbstractMapper
{
    protected function getMapping()
    {
        //there is no mapping
        return array();
    }

    protected function getFilterMapping()
    {
        return array(
            'firstName' => 'vorname',
            'realtyId'  => 'objekt_id',
            'lastName'  => 'nachname',
            'email'     => 'email',
            'phone'     => 'tel',
            'message'   => 'message',
        );
    }

    public function getSetter($apiPropertyName)
    {

    }

    public function getProperty($apiPropertyName)
    {

    }

    public function getType($apiPropertyName)
    {

    }
}
