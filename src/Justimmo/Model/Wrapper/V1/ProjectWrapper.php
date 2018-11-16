<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Project;
use Justimmo\Model\Garage;
use Justimmo\Pager\ListPager;
use Justimmo\Model\Mapper\V1\EmployeeMapper;

class ProjectWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id',
        'nummer',
        'titel',
        'beschreibung',
        'dreizeiler',
        'land',
        'bundesland',
        'plz',
        'ort',
        'strasse',
        'hausnummer',
        'naehe',
        'anzahl_etagen',
        'anzahl_dachgeschosse',
        'bauart_id',
        'baujahr',
        'laermpegel',
        'beziehbar',
        'ea_gueltig_bis',
        'ea_hwb',
        'ea_hwb_klasse',
        'ea_fgee',
        'ea_fgee_klasse',
        'zustand',
        'haus_zustand',
        'lagebewertung',
        'zustandsbewertung',
        'nutzungsart',
        'status',
        'sonstige_angaben',
        'freitext_1',
        'freitext_2',
        'lage',
        'referenz',
        'url',
        'fertigstellung',
        'verkaufsstart',
        'erstellt_am',
    );

    public function transformSingle($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->projekt)) {
            $xml = $xml->projekt;
        }

        $project = new Project();
        $this->map($this->simpleMapping, $xml, $project);

        if (isset($xml->nutzungsart)) {
            $project->setOccupancy(filter_var_array($this->attributesToArray($xml->nutzungsart->attributes()), FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($xml->status) && $xml->status->attributes()->semantic) {
            $string = (string)$xml->status->attributes()->semantic;

            $project->setProjectStateSemantic($string);
        }

        if (isset($xml->erstes_bild) && (((string) $xml->erstes_bild) != '')) {
            $project->addAttachment(new Attachment((string) $xml->erstes_bild));
        }

        if (isset($xml->bilder) && isset($xml->bilder->bild)) {
            $this->mapAttachmentGroup($xml->bilder->bild, $project, 'picture');
        }

        if (isset($xml->plaene) && isset($xml->plaene->bild)) {
            $this->mapAttachmentGroup($xml->plaene->bild, $project, 'picture', 'GRUNDRISS');
        }

        if (isset($xml->dokumente) && isset($xml->dokumente->dokument)) {
            $this->mapAttachmentGroup($xml->dokumente->dokument, $project, 'document');
        }

        if (isset($xml->videos) && isset($xml->videos->video)) {
            $this->mapAttachmentGroup($xml->videos->video, $project, 'video');
        }

        if (isset($xml->bilder360) && isset($xml->bilder360->bild)) {
            $this->mapAttachmentGroup($xml->bilder360->bild, $project, 'picture', 'bilder360');
        }

        if (isset($xml->links) && isset($xml->links->link)) {
            $this->mapAttachmentGroup($xml->links->link, $project, 'link');
        }

        if (isset($xml->immobilien_ids->id)) {
            foreach ($xml->immobilien_ids->id as $id) {
                $project->addRealtyId((int) $id);
            }
        }

        if (isset($xml->immobilien->immobilie)) {
            $wrapper = new RealtyWrapper(new RealtyMapper());
            foreach ($xml->immobilien->immobilie as $immobilie) {
                $realty = $wrapper->transformSingle($immobilie->asXML());
                $project->addRealty($realty);
            }
        }

        if (isset($xml->kontaktperson)) {
            $employeeWrapper = new EmployeeWrapper(new EmployeeMapper());
            $contact = $employeeWrapper->transformSingle($xml->kontaktperson->asXML());
            $project->setContact($contact);
        }

        if (isset($xml->objektkategorie)) {
            foreach ($xml->objektkategorie->user_defined_anyfield as $anyField) {
                foreach ($anyField->ji_kategorie as $kategorie) {
                    $attributes = $this->attributesToArray($kategorie);

                    if (array_key_exists('id', $attributes)) {
                        $project->addCategory($attributes['id'], (string)$kategorie);
                    }

                }
            }
        }

        if (isset($xml->stellplaetze)) {
            $key = 0;

            foreach ($xml->stellplaetze[0] as $stellplaetz) {
                $garage = new Garage((string) $stellplaetz->art, (string) $stellplaetz->name, (int) $stellplaetz->anzahl, (string) $stellplaetz->vermarktungsart, (double) $stellplaetz->brutto, (double) $stellplaetz->netto, (double) $stellplaetz->ust, (string) $stellplaetz->ust_typ, (double) $stellplaetz->ust_wert);

                $project->addGarage($key, $garage);

                $key++;
            }
        }

        if (isset($xml->geokoordinaten)) {
            $coord = $this->attributesToArray($xml->geokoordinaten->attributes());
            $project->setLatitude((double) $coord['breitengrad']);
            $project->setLongitude((double) $coord['laengengrad']);
        }

        if (isset($xml->residential_aggregation_data)) {
            $rad = $xml->residential_aggregation_data;

            if (isset($rad->area_from)) {
                $project->setAreaFrom((float)$rad->area_from);
            }
            if (isset($rad->area_to)) {
                $project->setAreaTo((float)$rad->area_to);
            }

            if (isset($rad->price_from)) {
                $project->setPriceFrom((float)$rad->price_from);
            }
            if (isset($rad->price_to)) {
                $project->setPriceTo((float)$rad->price_to);
            }

            if (isset($rad->rooms_from)) {
                $project->setRoomsFrom((float)$rad->rooms_from);
            }
            if (isset($rad->rooms_to)) {
                $project->setRoomsTo((float)$rad->rooms_to);
            }

            if (isset($rad->available)) {
                $project->setSubunitsAvailable((float)$rad->available);
            }
        }

        return $project;
    }

    public function transformList($data)
    {
        $xml = new \SimpleXMLElement($data);

        $list = new ListPager();
        foreach ($xml->projekt as $projekt) {
            $project = $this->transformSingle($projekt->asXML());
            $list->append($project);
        }

        if ($list->count() > 0) {
            $list->setMaxPerPage($list->count());
        }
        $list->setNbResults($list->count());

        return $list;
    }
}
