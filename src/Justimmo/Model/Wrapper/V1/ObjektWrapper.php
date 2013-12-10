<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Objekt;
use Justimmo\Model\Wrapper\WrapperInterface;
use Justimmo\Model\Zusatzkosten;

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
        'kaufpreis'          => 'double',
        'gesamtmiete'        => 'double',
        'nutzflaeche'        => 'string',
        'grundflaeche'       => 'string',
        'projekt_id'         => 'int',
        'status'             => 'string',
    );

    protected $geoMapping = array(
        'ort'               => 'string',
        'plz'               => 'string',
        'regionaler_zusatz' => 'string',
        'anzahl_etagen'     => 'int',
        'etage'             => 'string',
        'gemarkung'         => 'string',
        'flur'              => 'string',
        'flurstueck'        => 'string',
        'bundesland'        => 'string',
        'strasse'           => 'string',
        'tuernummer'        => 'string',
        'hausnummer'        => 'string',
    );

    protected $preisMapping = array(
        'kaufpreis'                  => 'double',
        'nettokaltmiete'             => 'double',
        'nebenkosten'                => 'double',
        'heizkosten'                 => 'double',
        'wohnbaufoerderung'          => 'double',
        'rendite'                    => 'double',
        'nettoertrag_jaehrlich'      => 'double',
        'nettoertrag_monatlich'      => 'double',
        'gesamtmiete_ust'            => 'double',
        'grunderwerbssteuer'         => 'double',
        'grundbucheintragung'        => 'double',
        'vertragserrichtungsgebuehr' => 'double',
        'kaution'                    => 'double',
    );

    /**
     * @param $data
     *
     * @return Objekt
     */
    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->immobilie)) {
            $xml = $xml->immobilie;
        }

        $objekt = new Objekt();

        //basic attributes from list view
        $this->map($this->simpleMapping, $xml, $objekt);

        //detailed attributes from detail view, OpenImmo
        if (isset($xml->verwaltung_techn)) {
            $objekt->setId((int) $xml->verwaltung_techn->objektnr_intern);
            $objekt->setObjektnummer((string) $xml->verwaltung_techn->objektnr_extern);
            $objekt->setProjektId((int) $xml->verwaltung_techn->projekt_id);
        }

        if (isset($xml->objektkategorie)) {
            if (isset($xml->objektkategorie->objektart)) {
                $objekt->setObjektart((string) $xml->objektkategorie->objektart->children()->getName());
            }
            if (isset($xml->objektkategorie->nutzungsart)) {
                $objekt->setNutzungsart($this->attributesToArray($xml->objektkategorie->nutzungsart->attributes()));
            }
            if (isset($xml->objektkategorie->vermarktungsart)) {
                $objekt->setVermarktungsart($this->attributesToArray($xml->objektkategorie->vermarktungsart->attributes()));
            }
        }

        if (isset($xml->freitexte)) {
            $objekt->setTitel((string) $xml->freitexte->objekttitel);
            $objekt->setAusstattBeschr((string) $xml->freitexte->ausstatt_beschr);
            $objekt->setObjektbeschreibung((string) $xml->freitexte->objektbeschreibung);
        }

        if (isset($xml->geo)) {
            $this->map($this->geoMapping, $xml->geo, $objekt);

            if (isset($xml->geo->geokoordinaten)) {
                $coord = $this->attributesToArray($xml->geo->geokoordinaten->attributes());
                $objekt->setBreitengrad((double) $coord['breitengrad']);
                $objekt->setLaengengrad((double) $coord['laengengrad']);
            }

            if (isset($xml->geo->land)) {
                $iso = $this->attributesToArray($xml->geo->land->attributes());
                if (array_key_exists('iso_land', $iso)) {
                    $objekt->setLand((string) $iso['iso_land']);
                }
            }
        }

        if (isset($xml->preise)) {
            $this->map($this->preisMapping, $xml->preise, $objekt);
            $objekt->setGesamtmiete($this->cast($xml->preise->warmmiete, 'double'));

            if (isset($xml->preise->waehrung)) {
                $iso = $this->attributesToArray($xml->preise->waehrung->attributes());
                if (array_key_exists('iso_waehrung', $iso)) {
                    $objekt->setWaehrung((string) $iso['iso_waehrung']);
                }
            }

            if (isset($xml->preise->zusatzkosten)) {
                foreach ($xml->preise->zusatzkosten[0] as $key => $zusatzkosten) {
                    $name = isset($zusatzkosten->name) ? $zusatzkosten->name : $key;
                    $objekt->addZusatzkosten($key, new Zusatzkosten((string) $name, (double) $zusatzkosten->brutto, (double) $zusatzkosten->netto, (double) $zusatzkosten->ust));
                }
            }
        }

        return $objekt;
    }

    /**
     * casts a simple xml element to a type
     *
     * @param        $xml
     * @param string $type
     *
     * @return float|int|null|string
     */
    protected function cast(\SimpleXMLElement $xml, $type = 'string')
    {
        if ($xml->count() == 0) {
            return null;
        }

        switch ($type) {
            case 'string':
                return (string) $xml;
            case 'int':
                return (int) $xml;
            case 'double':
                return (double) $xml;
            default:
                return $xml;
        }
    }

    /**
     * maps an mapping array between a SimpleXML and Objekt
     *
     * @param                   $mapping
     * @param \SimpleXMLElement $xml
     * @param Objekt            $objekt
     */
    protected function map($mapping, \SimpleXMLElement $xml, Objekt $objekt)
    {
        foreach ($mapping as $key => $cast) {
            if (isset($xml->$key)) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                $objekt->$setter($this->cast($xml->$key, $cast));
            }
        }
    }

    /**
     * converts the attributes of a SimpleXmlElement to an array
     *
     * @param \SimpleXMLElement $xml
     *
     * @return array
     */
    protected function attributesToArray(\SimpleXMLElement $xml)
    {
        $array = (array) $xml;

        return array_key_exists('@attributes', $array) ? $array['@attributes'] : array();
    }
}
