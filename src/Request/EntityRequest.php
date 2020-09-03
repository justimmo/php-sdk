<?php

namespace Justimmo\Api\Request;

interface EntityRequest extends ApiRequest
{
    /**
     * Returns class name for entities generated by this request
     *
     * @return string
     */
    public function getEntityClass();
}