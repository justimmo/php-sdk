<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\Country;

/**
 * @method $this filterByWithRealties($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class CountryRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    const FILTERS = [
        'withRealties',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/countries';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Country::class;
    }
}
