<?php

namespace Justimmo\Model\Wrapper\V1;

class BasicDataWrapper
{
    public function transformCountries($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->land as $land) {
            $return[(int) $land->id] = array(
                'name' => (string) $land->name,
                'iso2' => (string) $land->iso2,
                'iso3' => (string) $land->iso3,
            );
        }

        return $return;
    }
}
