<?php

namespace Justimmo\Api\Request;

interface JoinableRequest
{
    /**
     * Returns eligible filter key value pairs to be used for a joinable request
     *
     * If the return value is an empty array only the join will be performeb
     * Data will not be checked
     *
     * @return array
     */
    public function getJoinableFilters();
}
