<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Geo\ZipCode;

/**
 * @method $this sortByZip($direction = BaseApiRequest::ASC)
 */
class ZipCodeRequest extends BaseApiRequest
{
    use RealtyFilterable;

    const SORTS = [
        'zip',
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
