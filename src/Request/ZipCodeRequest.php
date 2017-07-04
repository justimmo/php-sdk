<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\ZipCode;

/**
 * @method $this filterByWithRealties($value)
 * @method $this filterByCountry($value)
 * @method $this filterByFederalState($value)
 * @method $this sortByZip($direction = BaseApiRequest::ASC)
 * @method $this sortByCity($direction = BaseApiRequest::ASC)
 */
class ZipCodeRequest extends BaseApiRequest
{
    const FIELD_COUNTRY       = 'country';
    const FIELD_CITY          = 'city';
    const FIELD_FEDERAL_STATE = 'federalState';
    const FIELD_REGION        = 'region';

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
