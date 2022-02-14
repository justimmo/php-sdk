<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Employee;
use Justimmo\Pager\ListPager;
use function array_key_exists;

class EmployeeWrapper extends AbstractWrapper
{
    protected $simpleMapping = array(
        'id',
        'email',
        'position',
        'vorname',
        'nachname',
        'handy',
        'tel',
        'fax',
        'kategorie',
        'titel',
        'tel_zentrale',
        'email_direkt',
        'tel_fax',
        'tel_handy',
        'name',
        'personennummer',
        'suffix',
        'bio',
        'strasse',
        'plz',
        'ort',
        'url',
    );

    public function transformSingle($data)
    {
        $xml = new \SimpleXMLElement($data);

        if (isset($xml->mitarbeiter)) {
            $xml = $xml->mitarbeiter;
        }

        $mitarbeiter = new Employee();
        $this->map($this->simpleMapping, $xml, $mitarbeiter);

        //format used in team calls
        if (isset($xml->bild) && isset($xml->bild->pfad) && (((string) $xml->bild->pfad) != '')) {
            $attachment = new Attachment((string) $xml->bild->pfad);
            $attachment->setGroup('PROFILBILD');
            if (isset($xml->bild->pfad_medium)) {
                $attachment->addData('medium', (string) $xml->bild->pfad_medium);
            }

            // add all formats
            foreach ($xml->bild->children() as $key => $size) {
                $attachment->addData((string)$key, (string)$size);
            }

            $mitarbeiter->addAttachment($attachment);
        }

        //format used in project and realty calls
        if (isset($xml->bild) && isset($xml->bild->medium) && (((string) $xml->bild->medium) != '')) {
            $attachment = new Attachment((string) $xml->bild->medium);
            $attachment->addData('medium', (string) $xml->bild->medium);
            $attachment->setGroup('PROFILBILD');
            if (isset($xml->bild->small)) {
                $attachment->addData('small', (string) $xml->bild->small);
            }
            if (isset($xml->bild->big)) {
                $attachment->addData('big', (string) $xml->bild->big);
            }

            // add all formats
            foreach ($xml->bild->children() as $key => $size) {
                $attachment->addData((string)$key, (string)$size);
            }

            $mitarbeiter->addAttachment($attachment);
        }

        if (isset($xml->anhaenge) && isset($xml->anhaenge->anhang)) {
            $this->mapAttachmentGroup($xml->anhaenge->anhang, $mitarbeiter);
        }

        return $mitarbeiter;
    }

    public function transformList($data)
    {
        $xml = new \SimpleXMLElement($data);

        $transformed = new ListPager();
        foreach ($xml->kategorie as $kategorie) {
            $attributes = $this->attributesToArray($kategorie);

            foreach ($kategorie->mitarbeiter as $mitarbeiter) {
                $member = $this->transformSingle($mitarbeiter->asXML());
                if (array_key_exists('name', $attributes)) {
                    $member->setCategory($attributes['name']);
                }
                $transformed->append($member);
            }
        }

        if ($transformed->count() > 0) {
            $transformed->setMaxPerPage($transformed->count());
        }
        $transformed->setNbResults($transformed->count());

        return $transformed;
    }

    /**
     * @inheritDoc
     */
    protected function mapAttachmentGroup(\SimpleXMLElement $xml, $attachmentAware, $type = null, $forceGroup = null)
    {
        foreach ($xml as $anhang) {
            $data = (array) $anhang->daten;
            $attributes = $this->attributesToArray($anhang);
            $group = $forceGroup ?: (array_key_exists('gruppe', $attributes) ? $attributes['gruppe'] : null);
            if (array_key_exists('pfad', $data)) {
                $data['big'] = $data['pfad'];

                $attachment = new Attachment($data['orig'] ?? $data['pfad'], $type, $group);
                $attachment->mergeData($data);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(array('vorschaubild' => $this->cast($anhang->vorschaubild)));
                }
                $attachment->setTitle($this->cast($anhang->anhangtitel));
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            } elseif (isset($anhang->pfad)) {
                if (isset($anhang->gruppe)) {
                    $group = strtoupper($this->cast($anhang->gruppe));
                }
                $attachment = new Attachment($this->cast($anhang->pfad), $type, $group);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(array('vorschaubild' => $this->cast($anhang->vorschaubild)));
                }
                $attachment->setTitle($this->cast($anhang->titel));
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            }
        }
    }

}
