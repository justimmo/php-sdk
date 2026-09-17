<?php

namespace Justimmo\Curl;

/**
 * Interface CurlRequestInterface
 *
 * what the api layer needs from a request, so it can be replaced in tests
 *
 * @package Justimmo\Curl
 */
interface CurlRequestInterface
{
    /**
     * sets a curl option, the key is one of the CURLOPT_* constants
     *
     * @return $this
     */
    public function setOption(int $key, mixed $value);

    /**
     * executes a get request and returns the response body
     *
     * @return mixed
     */
    public function get();

    /**
     * the transport error of the request, empty if there was none
     *
     * @return string|null
     */
    public function getError();

    /**
     * @return int|null
     */
    public function getStatusCode();

    /**
     * the raw response body
     *
     * @return string|null
     */
    public function getContent();

    /**
     * the content type of the response, decides how an error body is parsed
     *
     * @return string|null
     */
    public function getContentType();
}
