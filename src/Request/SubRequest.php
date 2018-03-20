<?php

namespace Justimmo\Api\Request;

interface SubRequest
{
    /**
     * Returns eligible filter key value pairs to be used for a sub request
     *
     * If the return value is an empty array only the connection with the SubRequest
     * data will be checked
     *
     * @return array
     */
    public function getSubFilters();

    /**
     * Returns fields to be used for the sub request
     *
     * These fields are shown for sub entities
     *
     * @return array
     */
    public function getSubFields();
}
