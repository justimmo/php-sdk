<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Region;

/**
 * @method $this filterByWithRealties($value)
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class RegionRequest extends BaseApiRequest
{
    const FIELD_COUNTRIES      = 'countries';
    const FIELD_FEDERAL_STATES = 'federalStates';
    const FIELD_ZIP_CODES      = 'zipCodes';

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
