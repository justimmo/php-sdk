<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Employee;

class EmployeeWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id'        => 'int',
        'email'     => 'string',
        'position'  => 'string',
        'vorname'   => array('type' => 'string', 'property' => 'firstName'),
        'nachname'  => array('type' => 'string', 'property' => 'lastName'),
        'handy'     => array('type' => 'string', 'property' => 'mobile'),
        'tel'       => array('type' => 'string', 'property' => 'phone'),
        'fax'       => array('type' => 'string', 'property' => 'fax'),
        'kategorie' => array('type' => 'string', 'property' => 'category'),
        'titel'     => array('type' => 'string', 'property' => 'title'),
    );

    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->mitarbeiter)) {
            $xml = $xml->mitarbeiter;
        }

        $mitarbeiter = new Employee();
        $this->map($this->simpleMapping, $xml, $mitarbeiter);

        if (isset($xml->bild) && isset($xml->bild->pfad) && (((string) $xml->bild->pfad) != '')) {
            $attachment = new Attachment((string) $xml->bild->pfad);
            if (isset($xml->bild->pfad_medium)) {
                $attachment->addData('medium', (string) $xml->bild->pfad_medium);
            }
            $mitarbeiter->addAttachment($attachment);

        }

        return $mitarbeiter;
    }

}