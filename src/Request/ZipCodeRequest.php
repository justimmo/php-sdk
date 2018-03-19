<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\ZipCode;

/**
 * @method $this withCountry()
 * @method $this withCity()
 * @method $this withFederalState()
 * @method $this withRegion()
 * @method $this filterByWithRealties($value)
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this sortByZip($direction = BaseApiRequest::ASC)
 * @method $this sortByCity($direction = BaseApiRequest::ASC)
 */
class ZipCodeRequest extends BaseApiRequest
{
    const FIELDS = [
        'country',
        'city',
        'federalState',
        'region',
    ];

    const FILTERS = [
        'withRealties',
        'country',
        'federalState',
    ];

    const SORTS = [
        'zip',
        'city',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/zip-codes';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return ZipCode::class;
    }
}
