<?php

namespace Justimmo\Model\Wrapper;

interface BasicDataWrapperInterface
{
    public function transformCountries($data);

    public function transformFederalStates($data);

    public function transformRealtyTypes($data);

    public function transformRealtyCategories($data);

    public function transformTenant($data);
}
