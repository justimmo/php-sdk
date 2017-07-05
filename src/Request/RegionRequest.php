<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Region;

/**
 * @method $this withCountries()
 * @method $this withFederalStates()
 * @method $this withZipCodes()
 * @method $this filterByWithRealties($value)
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RegionRequest extends BaseApiRequest
{
    const FIELDS = [
        'countries',
        'federalStates',
        'zipCodes',
    ];

    const FILTERS = [
        'withRealties',
        'country',
        'federalState',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/regions';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Region::class;
    }
}
