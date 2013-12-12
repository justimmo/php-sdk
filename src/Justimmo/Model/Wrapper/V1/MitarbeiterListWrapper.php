<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Pager\ListPager;

class MitarbeiterListWrapper extends AbstractWrapper
{
    public function transform($data)
    {
        $xml = new \SimpleXMLElement($data);
        $singleTransformer = new MitarbeiterWrapper();

        $transformed = new ListPager();
        foreach($xml->kategorie as $kategorie) {
            $attributes = $this->attributesToArray($kategorie);

            foreach ($kategorie->mitarbeiter as $mitarbeiter) {
                $member = $singleTransformer->transform($mitarbeiter->asXML());
                if (array_key_exists('name', $attributes)) {
                    $member->setKategorie($attributes['name']);
                }
                $transformed->append($member);
            }
        }

        $transformed->setMaxPerPage($transformed->count());
        $transformed->setNbResults($transformed->count());

        return $transformed;
    }
}
