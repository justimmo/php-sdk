<?php

namespace Justimmo\Tests;

class MockCurlRequest
{
    protected $statusCode = 200;

    protected $content;

    /**
     * MockCurlRequest constructor.
     *
     * @param     $content
     * @param int $statusCode
     */
    public function __construct($content, $statusCode)
    {
        $this->content    = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getError()
    {
        
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setOption()
    {

    }

    public function post()
    {

    }

    public function get()
    {

    }

    public function setParameter()
    {

    }
}
