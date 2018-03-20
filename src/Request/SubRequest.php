<?php

namespace Justimmo\Api\Request;

interface SubRequest
{
    /**
     * Returns eligible filter key value pairs to be used for a sub request
     *
     * Sub requests are used to filter realted entity data in fields. Eg realties in RealtyCategory
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
