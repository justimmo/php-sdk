<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Salutation;

/**
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 */
class SalutationRequest extends BaseApiRequest
{
    const SORTS = [
        'name',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/salutations';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Salutation::class;
    }
}
