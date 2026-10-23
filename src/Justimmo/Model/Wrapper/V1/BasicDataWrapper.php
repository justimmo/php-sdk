<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\PoliticalDistrict;
use Justimmo\Model\Wrapper\BasicDataWrapperInterface;
use SimpleXMLElement;
use function trim;

class BasicDataWrapper implements BasicDataWrapperInterface
{
    /**
     * Transforms the country list into an array keyed by the country id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array{name: string, iso2: string, iso3: string}>
     */
    public function transformCountries($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->land as $land) {
            $return[(int) $land->id] = [
                'name' => (string) $land->name,
                'iso2' => (string) $land->iso2,
                'iso3' => (string) $land->iso3,
            ];
        }

        return $return;
    }

    /**
     * Transforms the federal state list into an array keyed by the federal state id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array{name: string, countryId: int, fipsCode: string}>
     */
    public function transformFederalStates($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->bundesland as $bundesland) {
            $return[(int) $bundesland->id] = [
                'name'      => (string) $bundesland->name,
                'countryId' => (int) $bundesland->landid,
                'fipsCode'  => (string) $bundesland->fipscode,
            ];
        }

        return $return;
    }

    /**
     * Transforms the zip code list into a list of zip code and place pairs.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformZipCodes($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->postleitzahl as $postleitzahl) {
            $return[] = [
                'id'             => ((string) $postleitzahl->id === '') ? null : (int) $postleitzahl->id,
                'countryId'      => ((string) $postleitzahl->landid === '') ? null : (int) $postleitzahl->landid,
                'regionId'       => ((string) $postleitzahl->regionid === '') ? null : (int) $postleitzahl->regionid,
                'zipCode'        => trim((string) $postleitzahl->plz),
                'place'          => trim((string) $postleitzahl->ort),
                'federalStateId' => ((string) $postleitzahl->bundeslandid === '') ? null : (int) $postleitzahl->bundeslandid,
            ];
        }

        return $return;
    }

    /**
     * @deprecated
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     * @return array<int, string>
     */
    public function transformRegions($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->region as $region) {
            $return[(int) $region->id] = trim((string) $region->name);
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function transformPoliticalDistricts(mixed $data): array
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->politischer_bezirk as $politicalDistrict) {
            $id          = (int) $politicalDistrict->id;
            $return[$id] = new PoliticalDistrict($id, trim((string) $politicalDistrict->name));
        }

        return $return;
    }

    /**
     * Transforms the realty type list into an array keyed by the realty type id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyTypes($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->objektart as $objektart) {
            $return[(int) $objektart->id] = [
                'name'      => (string) $objektart->name,
                'key'       => (string) $objektart->key,
                'attribute' => (string) $objektart->attributename,
            ];
        }

        return $return;
    }

    /**
     * Transforms the realty category list into an array keyed by the category id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyCategories($data)
    {
        $xml = new SimpleXMLElement($data);

        $return = [];
        foreach ($xml->objektkategorie as $objektkategorie) {
            $return[(int) $objektkategorie->id] = [
                'name'         => (string) $objektkategorie->name,
                'sortableRank' => (int) $objektkategorie->sortablerank,
            ];
        }

        return $return;
    }

    /**
     * Transforms the tenant call into an array of its properties.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<string, mixed>
     */
    public function transformTenant($data)
    {
        $xml = new SimpleXMLElement($data);

        return (array) $xml->tenant;
    }
}
