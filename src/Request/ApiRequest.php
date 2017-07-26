<?php

namespace Justimmo\Api\Request;

interface ApiRequest
{
    /**
     * Returns the path to be called
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns an array to be sent as query string
     *
     * @return array
     */
    public function getQuery();

    /**
     * Returns HTTP method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns guzzle options for current request
     *
     * @return array
     */
    public function getGuzzleOptions();
}
