<?php

namespace Justimmo\Model\Wrapper;

use Justimmo\Model\PoliticalDistrict;

interface BasicDataWrapperInterface
{
    public function transformCountries($data);

    public function transformFederalStates($data);

    public function transformZipCodes($data);

    /**
     * @deprecated
     */
    public function transformRegions($data);

    /**
     * @return array<int, PoliticalDistrict>
     */
    public function transformPoliticalDistricts(mixed $data): array;

    public function transformRealtyTypes($data);

    public function transformRealtyCategories($data);

    public function transformTenant($data);
}
