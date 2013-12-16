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
    );

    public function transformSingle($data)
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

        $transformed->setMaxPerPage($transformed->count());
        $transformed->setNbResults($transformed->count());

        return $transformed;
    }

}