<?php

namespace Justimmo\Model\Wrapper;

use Justimmo\Model\PoliticalDistrict;
use Justimmo\Model\RealtyFeature;

interface BasicDataWrapperInterface
{
    /**
     * Transforms the country list into an array keyed by the country id.
     *
     * @param string $data the raw response of the api call
     *
     * @return array<int, array{name: string, iso2: string, iso3: string}>
     */
    public function transformCountries($data);

    /**
     * Transforms the federal state list into an array keyed by the federal state id.
     *
     * @param string $data the raw response of the api call
     *
     * @return array<int, array{name: string, countryId: int, fipsCode: string}>
     */
    public function transformFederalStates($data);

    /**
     * Transforms the zip code list into a list of zip code and place pairs.
     *
     * @deprecated since 1.8.0, removed in 2.0.0, with findZipCodes()
     *
     * @param string $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformZipCodes($data);

    /**
     * @deprecated since 1.8.0, removed in 2.0.0, with findRegions()
     *
     * @param string $data the raw response of the api call
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
     * @param string $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyTypes($data);

    /**
     * Transforms the realty category list into an array keyed by the category id.
     *
     * @param string $data the raw response of the api call
     *
     * @return array<int, array<string, mixed>>
     */
    public function transformRealtyCategories($data);

    /**
     * Transforms the realty feature list into RealtyFeature objects keyed by the feature id.
     *
     * @param ?string $data the raw response of the api call
     *
     * @return array<int, RealtyFeature>
     */
    public function transformRealtyFeatures(?string $data): array;

    /**
     * Transforms the tenant call into an array of its properties.
     *
     * @param string $data the raw response of the api call
     *
     * @return array<string, mixed>
     */
    public function transformTenant($data);
}
