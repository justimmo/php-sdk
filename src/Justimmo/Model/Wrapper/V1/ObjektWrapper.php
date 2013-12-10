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
        foreach ($this->simpleMapping as $key => $cast) {
            if (isset($xml->$key)) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

                if ($cast == 'string') {
                    $objekt->$setter((string) $xml->$key);
                } elseif ($cast == 'int') {
                    $objekt->$setter((int) $xml->$key);
                }
                elseif ($cast == 'double') {
                    $objekt->$setter((double) $xml->$key);
                }
            }
        }

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
            $objekt->setOrt((string) $xml->geo->ort);
            $objekt->setPlz((string) $xml->geo->plz);
            $objekt->setRegionalerZusatz((string) $xml->geo->regionaler_zusatz);
            $objekt->setAnzahlEtagen((int) $xml->geo->anzahl_etagen);
            $objekt->setEtage((string) $xml->geo->etage);
            $objekt->setGemarkung((string) $xml->geo->gemarkung);
            $objekt->setFlur((string) $xml->geo->flur);
            $objekt->setFlurstueck((string) $xml->geo->flurstueck);
            $objekt->setBundesland((string) $xml->geo->bundesland);
            $objekt->setStrasse((string) $xml->geo->strasse);
            $objekt->setTuernummer((string) $xml->geo->tuernummer);
            $objekt->setHausnummer((string) $xml->geo->hausnummer);

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
            $objekt->setKaufpreis((double) $xml->preise->kaufpreis);
            $objekt->setGesamtmiete((double) $xml->preise->warmmiete);
            $objekt->setNettoKaltMiete((double) $xml->preise->nettokaltmiete);
            $objekt->setNebenkosten((double) $xml->preise->nebenkosten);
            $objekt->setHeizkosten((double) $xml->preise->heizkosten);
            $objekt->setWohnbaufoerderung((double) $xml->preise->wohnbaufoerderung);
            $objekt->setRendite((double) $xml->preise->rendite);
            $objekt->setNettoertragJaehrlich((double) $xml->preise->nettoertrag_jaehrlich);
            $objekt->setNettoertrageMonatlich((double) $xml->preise->nettoertrag_monatlich);
            $objekt->setGesamtMieteUst((double) $xml->preise->gesamtmiete_ust);

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
