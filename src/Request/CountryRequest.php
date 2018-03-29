<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\Country;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class CountryRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const SORTS = [
        'name',
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
