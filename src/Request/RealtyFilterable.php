<?php

namespace Justimmo\Api\Request;

/**
 * @method $this joinRequest($name, JoinableRequest $request)
 */
trait RealtyFilterable
{
    /**
     * Filters data by connected realties which match the given RealtyRequest
     *
     * @param RealtyRequest $request
     *
     * @return $this
     */
    public function filterByRealties(RealtyRequest $request)
    {
        return $this->joinRequest('realties', $request);
    }
}
