<?php

namespace Justimmo\Api\Tests\Request;

use Justimmo\Api\Entity\Salutation;
use Justimmo\Api\Request\SalutationRequest;

class SalutationRequestTest extends RequestTestCase
{
    const ENTITY_CLASS = Salutation::class;

    const PATH_PREFIX = '/salutations';

    const SORTS = [
        'name',
    ];

    /**
     * @inheritdoc
     */
    protected function getRequest()
    {
        return new SalutationRequest();
    }
}

