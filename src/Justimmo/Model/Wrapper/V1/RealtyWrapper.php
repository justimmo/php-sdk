<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\EnergyPass;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Realty;
use Justimmo\Model\AdditionalCosts;
use Justimmo\Pager\ListPager;

class RealtyWrapper extends AbstractWrapper
{
    /**
     * simple attributes mostly used in list call
     *
     * @var array
     */
    protected $simpleMapping = array(
        'id',
        'objektnummer',
        'titel',
        'dreizeiler',
        'naehe',
        'objektbeschreibung',
        'sonstige_angaben',
        'etage',
        'tuernummer',
        'plz',
        'ort',
        'kaufpreis',
        'gesamtmiete',
        'nutzflaeche',
        'grundflaeche',
        'gesamtflaeche',
        'wohnflaeche',
        'bueroflaeche',
        'lagerflaeche',
        'projekt_id',
        'status',
        'status_id',
        'anzahl_zimmer',
        'wohnbaufoerderung',
        'anzahl_loggias',
        'loggias_flaeche',
        'anzahl_balkons',
        'balkons_flaeche',
        'anzahl_terrassen',
        'terrassen_flaeche',
        'gartenflaeche',
        'vermittelt_am',
        'erstellt_am',
        'aktualisiert_am',
    );

    protected $geoMapping = array(
        'ort',
        'plz',
        'regionaler_zusatz',
        'anzahl_etagen',
        'etage',
        'gemarkung',
        'flur',
        'flurstueck',
        'bundesland',
        'strasse',
        'tuernummer',
        'hausnummer',
        'stiege',
    );

    protected $preisMapping = array(
        'kaufpreis',
        'kaufpreisnetto',
        'nettokaltmiete',
        'nebenkosten',
        'heizkosten',
        'wohnbaufoerderung',
        'rendite',
        'nettoertrag_jaehrlich',
        'nettoertrag_monatlich',
        'gesamtmiete_ust',
        'kaution',
        'kaution_text',
        'abstand',
        'aussen_courtage',
        'kaufpreis_pro_qm',
        'mietpreis_pro_qm',
        'betriebskosten_pro_qm',
        'betriebskosten_pro_qm',
        'freitext_preis',
    );

    protected $flaechenMapping = array(
        'nutzflaeche',
        'teilbar_ab',
        'grundflaeche',
        'wohnflaeche',
        'gesamtflaeche',
        'anzahl_zimmer',
        'anzahl_badezimmer',
        'anzahl_sep_wc',
        'anzahl_balkon_terrassen',
        'balkon_terrasse_flaeche',
        'anzahl_balkone',
        'anzahl_terrassen',
        'gartenflaeche',
        'kellerflaeche',
        'bueroflaeche',
        'lagerflaeche',
        'anzahl_loggias',
        'loggias_flaeche',
        'balkons_flaeche',
        'terrassen_flaeche',
        'anzahl_garagen',
        'garagen_flaeche',
        'anzahl_stellplaetze',
        'stellplatz_flaeche',
        'anzahl_abstellraum',
        'verbaubare_flaeche',
    );

    protected $energyMapping = array(
        'epass_hwbwert',
        'epass_hwbklasse',
        'epass_fgeewert',
        'epass_fgeeklasse',
    );

    public function transformList($data)
    {
        $xml = new \SimpleXMLElement($data);

        $transformed = new ListPager();
        $transformed->setNbResults((int) $xml->{'query-result'}->count);

        if (isset($xml->immobilie)) {
            foreach ($xml->immobilie as $immobilie) {
                $objekt = $this->transformSingle($immobilie->asXML());
                $transformed->append($objekt);
            }
        }
        if ($transformed->count() > 0) {
            $transformed->setMaxPerPage($transformed->count());
        }

        return $transformed;
    }

    /**
     * @param $data
     *
     * @return Realty
     */
    public function transformSingle($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->immobilie)) {
            $xml = $xml->immobilie;
        }

        $objekt = new Realty();

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
            $objekt->setPropertyNumber((string) $xml->verwaltung_techn->objektnr_extern);
            $objekt->setProjectId((int) $xml->verwaltung_techn->projekt_id);

            foreach ($xml->verwaltung_techn->user_defined_simplefield as $simpleField) {
                $this->mapSimpleField($simpleField, $objekt);
            }
        }

        if (isset($xml->verwaltung_objekt)) {
            $objekt->setStatus($this->cast($xml->verwaltung_objekt->status));
            $objekt->setStatusId($this->cast($xml->verwaltung_objekt->status_id));
            $objekt->setAvailableFrom($this->cast($xml->verwaltung_objekt->verfuegbar_ab));
            if (isset($xml->verwaltung_objekt->max_mietdauer)) {
                $objekt->setRentDuration($this->cast($xml->verwaltung_objekt->max_mietdauer, 'int'));
                if ($xml->verwaltung_objekt->max_mietdauer->attributes()->max_dauer == 'JAHR') {
                    $objekt->setRentDurationType('year');
                } elseif ($xml->verwaltung_objekt->max_mietdauer->attributes()->max_dauer == 'MONAT') {
                    $objekt->setRentDurationType('month');
                }
            }

            if (isset($xml->verwaltung_objekt->user_defined_anyfield) && isset($xml->verwaltung_objekt->user_defined_anyfield->justimmo_kategorie)) {
                foreach ($xml->verwaltung_objekt->user_defined_anyfield->justimmo_kategorie as $category) {
                    $objekt->addCategory((int) $category['id'], (string) $category);
                }
            }

            foreach ($xml->verwaltung_objekt->user_defined_simplefield as $simpleField) {
                $this->mapSimpleField($simpleField, $objekt);
            }
        }

        if (isset($xml->objektkategorie)) {
            if (isset($xml->objektkategorie->objektart)) {
                $objekt->setRealtyType((string) $xml->objektkategorie->objektart->children()->getName());
                $objekt->setSubRealtyType((string) $xml->objektkategorie->objektart->children()->attributes());
            }
            if (isset($xml->objektkategorie->nutzungsart)) {
                $objekt->setOccupancy(filter_var_array($this->attributesToArray($xml->objektkategorie->nutzungsart->attributes()), FILTER_VALIDATE_BOOLEAN));
            }
            if (isset($xml->objektkategorie->vermarktungsart)) {
                $objekt->setMarketingType(filter_var_array($this->attributesToArray($xml->objektkategorie->vermarktungsart->attributes()), FILTER_VALIDATE_BOOLEAN));
            }

            foreach ($xml->objektkategorie->user_defined_simplefield as $simpleField) {
                $this->mapSimpleField($simpleField, $objekt);
            }

            foreach ($xml->objektkategorie->user_defined_anyfield as $anyField) {
                foreach ($anyField->ji_kategorie as $kategorie) {
                    $attributes = $this->attributesToArray($kategorie);

                    if (array_key_exists('id', $attributes)) {
                        $objekt->addCategory($attributes['id'], (string)$kategorie);
                    }
                }
            }
        }

        if (isset($xml->freitexte)) {
            $objekt->setTitle((string) $xml->freitexte->objekttitel);
            $objekt->setEquipmentDescription((string) $xml->freitexte->ausstatt_beschr);
            $objekt->setDescription((string) $xml->freitexte->objektbeschreibung);
            $objekt->setOtherInformation((string) $xml->freitexte->sonstige_angaben);
            if (isset($xml->freitexte->lage)) {
                $objekt->setLocality((string) $xml->freitexte->lage);
            }

            if (isset($xml->freitexte->user_defined_anyfield)) {
                if (isset($xml->freitexte->user_defined_anyfield->justimmo_freitext1)) {
                    $objekt->setFreetext1((string) $xml->freitexte->user_defined_anyfield->justimmo_freitext1);
                }
                if (isset($xml->freitexte->user_defined_anyfield->justimmo_freitext2)) {
                    $objekt->setFreetext2((string) $xml->freitexte->user_defined_anyfield->justimmo_freitext2);
                }
                if (isset($xml->freitexte->user_defined_anyfield->justimmo_freitext3)) {
                    $objekt->setFreetext3((string) $xml->freitexte->user_defined_anyfield->justimmo_freitext3);
                }
            }
        }

        if (isset($xml->geo)) {
            $this->map($this->geoMapping, $xml->geo, $objekt);

            if (isset($xml->geo->geokoordinaten)) {
                $coord = $this->attributesToArray($xml->geo->geokoordinaten->attributes());
                $objekt->setLatitude((double) $coord['breitengrad']);
                $objekt->setLongitude((double) $coord['laengengrad']);
            }

            if (isset($xml->geo->land)) {
                $iso = $this->attributesToArray($xml->geo->land->attributes());
                if (array_key_exists('iso_land', $iso)) {
                    $objekt->setCountry((string) $iso['iso_land']);
                }
            }

            foreach ($xml->geo->user_defined_simplefield as $simpleField) {
                $this->mapSimpleField($simpleField, $objekt);
            }
        }

        if (isset($xml->preise)) {
            $this->map($this->preisMapping, $xml->preise, $objekt);
            $objekt->setTotalRent($this->cast($xml->preise->warmmiete, 'double'));

            if (isset($xml->preise->kaufpreisnetto) && isset($xml->preise->kaufpreisnetto['kaufpreisust'])) {
                $objekt->setPurchasePriceVat($this->cast($xml->preise->kaufpreisnetto['kaufpreisust'], 'double'));
            }

            if (isset($xml->preise->waehrung)) {
                $iso = $this->attributesToArray($xml->preise->waehrung->attributes());
                if (array_key_exists('iso_waehrung', $iso)) {
                    $objekt->setCurrency((string) $iso['iso_waehrung']);
                }
            }

            foreach ($xml->preise->user_defined_simplefield as $simpleField) {
                $this->mapSimpleField($simpleField, $objekt);
            }

            if (isset($xml->preise->zusatzkosten)) {
                foreach ($xml->preise->zusatzkosten[0] as $key => $zusatzkosten) {
                    $name = isset($zusatzkosten->name) ? $zusatzkosten->name : $key;
                    $costs = new AdditionalCosts((string) $name, (double) $zusatzkosten->brutto, (double) $zusatzkosten->netto, (double) $zusatzkosten->ust, (string) $zusatzkosten->ust_typ, (double) $zusatzkosten->ust_berechneter_wert, (double) $zusatzkosten->ust_wert);

                    if (isset($zusatzkosten->optional)) {
                        $costs->setOptional(filter_var((string) $zusatzkosten->optional, FILTER_VALIDATE_BOOLEAN));
                    }

                    $objekt->addAdditionalCosts($key, $costs);
                }
            }

            if (isset($xml->preise->nettomieteprom2von)) {
                $objekt->setRentPerSqmFrom((float) $xml->preise->nettomieteprom2von);
                $objekt->setRentPerSqm((float) $xml->preise->nettomieteprom2von->attributes()->nettomieteprom2bis);
            }
            if (isset($xml->preise->nebenkostenprom2von)) {
                $objekt->setOperatingCostsPerSqmFrom((float) $xml->preise->nebenkostenprom2von);
                $objekt->setOperatingCostsPerSqm((float) $xml->preise->nebenkostenprom2von->attributes()->nebenkostenprom2bis);
            }

            if (isset($xml->preise->miete)) {
                $objekt->setRentNet((float) $xml->preise->miete->netto);
                $objekt->setRentGross((float) $xml->preise->miete->brutto);
                $objekt->setRentVatType((string) $xml->preise->miete->ust_typ);
                $objekt->setRentVatInput((float) $xml->preise->miete->ust_wert);
                $objekt->setRentVat((float) $xml->preise->miete->ust);
                $objekt->setRentVatValue((float) $xml->preise->miete->ust_berechneter_wert);
            }
        }

        if (isset($xml->anhaenge) && isset($xml->anhaenge->anhang)) {
            $this->mapAttachmentGroup($xml->anhaenge->anhang, $objekt);
        }

        if (isset($xml->videos) && isset($xml->videos->video)) {
            $this->mapAttachmentGroup($xml->videos->video, $objekt);
        }

        if (isset($xml->links) && isset($xml->links->link)) {
            $this->mapAttachmentGroup($xml->links->link, $objekt, 'link');
        }

        if (isset($xml->flaechen)) {
            $this->map($this->flaechenMapping, $xml->flaechen, $objekt);
            $objekt->setSurfaceArea($this->cast($xml->flaechen->grundstuecksflaeche));
        }

        if (isset($xml->zustand_angaben)) {
            $objekt->setYearBuilt($this->cast($xml->zustand_angaben->baujahr, 'int'));

            $data = $this->attributesToArray($xml->zustand_angaben->zustand);
            if (array_key_exists('zustand_art', $data)) {
                $objekt->setCondition($data['zustand_art']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->alter);
            if (array_key_exists('alter_attr', $data)) {
                $objekt->setAge($data['alter_attr']);
            }

            $data = $this->attributesToArray($xml->zustand_angaben->erschliessung);
            if (array_key_exists('erschl_attr', $data)) {
                $objekt->setInfrastructureProvision($data['erschl_attr']);
            }

            if (isset($xml->zustand_angaben->energiepass)) {
                $energiepass = new EnergyPass();
                $energiepass
                    ->setEpart($this->cast($xml->zustand_angaben->energiepass->epart))
                    ->setValidUntil($this->cast($xml->zustand_angaben->energiepass->gueltig_bis, 'datetime'));

                foreach ($xml->zustand_angaben->user_defined_simplefield as $simpleField) {
                    $this->mapSimpleField($simpleField, $energiepass);
                }

                $objekt->setEnergyPass($energiepass);
            }

            if (isset($xml->ausstattung[0])) {
                /** @var \SimpleXMLElement $element */
                foreach ($xml->ausstattung[0] as $key => $element) {
                    if ((string) $element === "true" || (int) $element === 1) {
                        $objekt->addEquipment($key, $key);
                    } elseif ($element->attributes()->count()) {
                        $attributes = $this->attributesToArray($element);
                        $value      = array();
                        foreach ($attributes as $k => $v) {
                            if ($v === "true" || $v == 1) {
                                $value[] = $k;
                            } else {
                                $value[$k] = $v;
                            }
                        }
                        $objekt->addEquipment($key, count($value) > 1 || (array_keys($value) !== range(0, count($value) - 1)) ? $value : $value[0]);
                    } else {
                        $objekt->addEquipment($key, (string) $element);
                    }
                }
            }
        }

        if (isset($xml->kontaktperson)) {
            $employeeWrapper = new EmployeeWrapper(new EmployeeMapper());
            $contact = $employeeWrapper->transformSingle($xml->kontaktperson->asXML());
            $objekt->setContact($contact);
        }

        return $objekt;
    }

    /**
     * @param \SimpleXMLElement $simpleField
     * @param $model
     */
    protected function mapSimpleField(\SimpleXMLElement $simpleField, $model) {
        $attributes = $this->attributesToArray($simpleField);
        if (array_key_exists('feldname', $attributes)) {
            $setter = $this->mapper->getSetter($attributes['feldname']);

            if (method_exists($this->mapper, $setter)) {
                $this->mapper->$setter($simpleField, $model);

            } elseif (method_exists($model, $setter)) {
                $model->$setter($this->cast($simpleField, $this->mapper->getType($attributes['feldname'])));
            }
        }
    }
}
