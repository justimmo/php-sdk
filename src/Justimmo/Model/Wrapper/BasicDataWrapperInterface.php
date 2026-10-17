<?php

namespace Justimmo\Model\Wrapper;

use Justimmo\Model\PoliticalDistrict;

interface BasicDataWrapperInterface
{
    /**
     * Transforms the country list into an array keyed by the country id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array{name: string, iso2: string, iso3: string}>
     */
    public function transformCountries($data);

    /**
     * Transforms the federal state list into an array keyed by the federal state id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array{name: string, countryId: int, fipsCode: string}>
     */
    public function transformFederalStates($data);

    /**
     * Transforms the zip code list into a list of zip code and place pairs.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformZipCodes($data);

    /**
     * @deprecated
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     * @return array<int, string>
     */
    public function transformRegions($data);

    /**
     * @return array<int, PoliticalDistrict>
     */
    public function transformPoliticalDistricts(mixed $data): array;

    /**
     * Transforms the realty type list into an array keyed by the realty type id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyTypes($data);

    /**
     * Transforms the realty category list into an array keyed by the category id.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyCategories($data);

    /**
     * Transforms the tenant call into an array of its properties.
     *
     * @param string|\SimpleXMLElement $data the raw response of the api call
     *
     * @return array<string, mixed>
     */
    public function transformTenant($data);
}
