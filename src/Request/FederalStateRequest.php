<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\FederalState;

/**
 * @method $this withCountry()
 * @method $this filterByWithRealties($value)
 * @method $this filterByCountry($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class FederalStateRequest extends BaseApiRequest
{
    const FIELDS = [
        'country',
    ];

    const FILTERS = [
        'withRealties',
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