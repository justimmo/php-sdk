<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Energiepass;
use Justimmo\Model\Objekt;
use Justimmo\Model\Zusatzkosten;

class ObjektWrapper extends AbstractWrapper
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

    protected $flaechenMapping = array(
        'nutzflaeche'             => 'double',
        'grundflaeche'            => 'double',
        'wohnflaeche'             => 'double',
        'gesamtflaeche'           => 'double',
        'anzahl_zimmer'           => 'int',
        'anzahl_badezimmer'       => 'int',
        'anzahl_sep_wc'           => 'int',
        'anzahl_balkon_terrassen' => 'int',
        'balkon_terrasse_flaeche' => 'double',
        'anzahl_balkone'          => 'int',
        'anzahl_terrassen'        => 'int',
        'gartenflaeche'           => 'double',
        'kellerflaeche'           => 'double',
        'bueroflaeche'            => 'double',
        'lagerflaeche'            => 'double',
        'anzahl_loggias'          => 'int',
        'loggias_flaeche'         => 'double',
        'balkons_flaeche'         => array('type' => 'double', 'setter' => 'setBalkonFlaeche'),
        'terrassen_flaeche'       => 'double',
        'anzahl_garagen'          => 'int',
        'anzahl_abstellraum'      => 'int',
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

        //list object attachment mapping
        if (isset($xml->erstes_bild)) {
            $objekt->addAttachment(new Attachment((string) $xml->erstes_bild));
        }
        if (isset($xml->zweites_bild)) {
            $objekt->addAttachment(new Attachment((string) $xml->zweites_bild));
        }

        //detailed attributes from detail view, OpenImmo
        if (isset($xml->verwaltung_techn)) {
            $objekt->setId((int) $xml->verwaltung_techn->objektnr_intern);
            $objekt->setObjektnummer((string) $xml->verwaltung_techn->objektnr_extern);
            $objekt->setProjektId((int) $xml->verwaltung_techn->projekt_id);
        }

        if (isset($xml->verwaltung_objekt)) {
            $objekt->setStatus($this->cast($xml->verwaltung_objekt->status));
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

        if (isset($xml->anhaenge) && isset($xml->anhaenge->anhang)) {
            $this->mapAttachmentGroup($xml->anhaenge->anhang, $objekt);
        }

        if (isset($xml->dokumente) && isset($xml->dokumente->dokument)) {
            $this->mapAttachmentGroup($xml->dokumente->dokument, $objekt, 'document');
        }

        if (isset($xml->videos) && isset($xml->videos->video)) {
            $this->mapAttachmentGroup($xml->videos->video, $objekt, 'video');
        }

        if (isset($xml->bilder360) && isset($xml->bilder360->pfad)) {
            foreach ($xml->bilder360->pfad as $anhang) {
                $attachment = new Attachment($this->cast($anhang), 'bilder360');
                $objekt->addAttachment($attachment);
            }
        }

        if (isset($xml->flaechen)) {
            $this->map($this->flaechenMapping, $xml->flaechen, $objekt);
            $objekt->setGrundflaeche($this->cast($xml->flaechen->grundstuecksflaeche));
        }

        if (isset($xml->zustand_angaben)) {
            $objekt->setBaujahr($this->cast($xml->zustand_angaben->baujahr, 'int'));

            $data = $this->attributesToArray($xml->zustand_angaben->zustand);
            if (array_key_exists('zustand_art', $data)) {
                $objekt->setZustand($data['zustand_art']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->alter);
            if (array_key_exists('alter_attr', $data)) {
                $objekt->setZustand($data['alter_attr']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->erschliessung);
            if (array_key_exists('erschl_attr', $data)) {
                $objekt->setZustand($data['zustand_art']);
            }

            if (isset($xml->zustand_angaben->energiepass)) {
                $energiepass = new Energiepass();
                $energiepass
                    ->setEpart($this->cast($xml->zustand_angaben->energiepass->epart))
                    ->setGueltigBis($this->cast($xml->zustand_angaben->energiepass->gueltig_bis, 'datetime'));

                foreach ($xml->zustand_angaben->energiepass->epart->user_defined_simplefield as $simpleField) {
                    $attributes = $this->attributesToArray($simpleField);
                    if (array_key_exists('feldname', $attributes)) {
                        $setter = $this->buildSetter(str_replace('epass_', '', $attributes['feldname']));
                        $energiepass->$setter($this->cast($simpleField));
                    }
                }

                $objekt->setEnergiepass($energiepass);
            }

            if (isset($xml->ausstattung[0])) {
                /** @var \SimpleXMLElement $element */
                foreach ($xml->ausstattung[0] as $key => $element) {
                    if ((int) $element === 1) {
                        $objekt->addAusstattung($key, $key);
                    } elseif ($element->attributes()->count()) {
                        $attributes = $this->attributesToArray($element);
                        $value = array();
                        foreach ($attributes as $k => $v) {
                            if ($v == 1) {
                                $value[] = $k;
                            } else {
                                $value[$k][] = $v;
                            }
                        }
                        $objekt->addAusstattung($key, count($value) > 1 ? $value : $value[0]);
                    } else {
                        $objekt->addAusstattung($key, (string) $element);
                    }
                }
            }

        }

        return $objekt;
    }

    /**
     * @param \SimpleXMLElement      $xml
     * @param null                   $type
     *
     * @param \Justimmo\Model\Objekt $objekt
     *
     * @internal param array $data
     * @return \Justimmo\Model\Attachment|null
     */
    protected function mapAttachmentGroup(\SimpleXMLElement $xml, Objekt $objekt, $type = null)
    {
        foreach ($xml as $anhang) {
            $data = (array) $anhang->daten;
            if (array_key_exists('pfad', $data)) {
                $attachment = new Attachment($data['pfad'], $type);
                $attachment->setData($data);
                $attachment->setTitle($this->cast($anhang->anhangtitel));
                $objekt->addAttachment($attachment);
            } elseif (isset($anhang->pfad)) {
                $attachment = new Attachment($this->cast($anhang->pfad), $type);
                $attachment->setTitle($this->cast($anhang->titel));
                $objekt->addAttachment($attachment);
            }
        }
    }
}
