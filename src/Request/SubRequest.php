<?php

namespace Justimmo\Api\Request;

interface SubRequest
{
    /**
     * Returns eligible filter key value pairs to be used for a sub request
     *
     * @return array
     */
    public function getSubFilters();
}
