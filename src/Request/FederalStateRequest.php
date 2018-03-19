<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\FederalState;

/**
 * @method $this withCountry()
 * @method $this filterByCountry($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FederalStateRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const FIELDS = [
        'country',
    ];

    const FILTERS = [
        'country',
    ];

    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/federal-states';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return FederalState::class;
    }
}
