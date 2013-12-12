<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mitarbeiter;

class MitarbeiterWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id'        => 'int',
        'vorname'   => 'string',
        'nachname'  => 'string',
        'handy'     => 'string',
        'tel'       => 'string',
        'fax'       => 'string',
        'position'  => 'string',
        'kategorie' => 'string',
        'email'     => 'string',
        'titel'     => 'string',
    );

    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->mitarbeiter)) {
            $xml = $xml->mitarbeiter;
        }

        $mitarbeiter = new Mitarbeiter();
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