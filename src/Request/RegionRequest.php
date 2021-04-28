<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\Region;

/**
 * @method $this withCountries()
 * @method $this withFederalStates()
 * @method $this withZipCodes()
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this sortById($direction = BaseApiRequest::ASC)
 */
class RegionRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const FIELDS = [
        'countries',
        'federalStates',
        'zipCodes',
    ];

    const FILTERS = [
        'country',
        'federalState',
    ];

    const SORTS = [
        'id',
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
