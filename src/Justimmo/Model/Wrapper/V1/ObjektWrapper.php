<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Objekt;
use Justimmo\Model\Wrapper\WrapperInterface;

class ObjektWrapper implements WrapperInterface
{
    /**
     * simple attributes mostly used in list call
     *
     * @var array
     */
    protected $simpleMapping = array(
        'id'                 => 'int',
        'objektnummer'       => 'string',
        'titel'              => 'string',
        'dreizeiler'         => 'string',
        'naehe'              => 'string',
        'objektbeschreibung' => 'string',
        'etage'              => 'string',
        'tuernummer'         => 'string',
        'plz'                => 'string',
        'ort'                => 'string',
        'kaufpreis'          => 'string',
        'gesamtmiete'        => 'string',
        'nutzflaeche'        => 'string',
        'grundflaeche'       => 'string',
        'projekt_id'         => 'int',
        'status'             => 'string',
    );

    /**
     * @param $data
     *
     * @return Objekt
     */
    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);

        $objekt = new Objekt();

        foreach ($this->simpleMapping as $key => $cast) {
            if (isset($xml->$key)) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

                if ($cast == 'string') {
                    $objekt->$setter(trim((string)$xml->$key));
                } elseif ($cast == 'int') {
                    $objekt->$setter((int)$xml->$key);
                }
            }
        }

        return $objekt;
    }
}
