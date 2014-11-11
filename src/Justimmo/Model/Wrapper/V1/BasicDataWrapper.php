<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Wrapper\BasicDataWrapperInterface;

class BasicDataWrapper implements BasicDataWrapperInterface
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

    public function transformFederalStates($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->bundesland as $bundesland) {
            $return[(int) $bundesland->id] = array(
                'name'      => (string) $bundesland->name,
                'countryId' => (int) $bundesland->landid,
                'fipsCode'  => (string) $bundesland->fipscode,
            );
        }

        return $return;
    }

    public function transformZipCodes($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->postleitzahl as $postleitzahl) {
            $return[(int) $postleitzahl->id] = array(
                'countryId'      => (int) $postleitzahl->landid,
                'regionId'       => (int) $postleitzahl->regionid,
                'zipCode'        => trim((string) $postleitzahl->plz),
                'place'          => trim((string) $postleitzahl->ort),
                'federalStateId' => (int) $postleitzahl->bundeslandid,
            );
        }

        return $return;
    }

    public function transformRegions($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->region as $region) {
            $return[(int) $region->id] = trim((string) $region->name);
        }

        return $return;
    }

    public function transformRealtyTypes($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->objektart as $objektart) {
            $return[(int) $objektart->id] = array(
                'name'      => (string) $objektart->name,
                'key'       => (string) $objektart->key,
                'attribute' => (string) $objektart->attributename,
            );
        }

        return $return;
    }

    public function transformRealtyCategories($data)
    {
        $xml = new \SimpleXMLElement($data);

        $return = array();
        foreach ($xml->objektkategorie as $objektkategorie) {
            $return[(int) $objektkategorie->id] = array(
                'name'         => (string) $objektkategorie->name,
                'sortableRank' => (int) $objektkategorie->sortablerank,
            );
        }

        return $return;
    }
}
