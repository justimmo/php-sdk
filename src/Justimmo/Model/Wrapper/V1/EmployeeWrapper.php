<?php
namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Employee;
use Justimmo\Pager\ListPager;

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

}
