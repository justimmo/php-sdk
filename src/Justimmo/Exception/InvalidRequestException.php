<?php

namespace Justimmo\Exception;

/**
 * Class InvalidRequestException
 *
 * thrown when client request is invalid, status code 400-499
 *
 * @package Justimmo\Exception
 */
class InvalidRequestException extends \Exception implements JustimmoException
{
    /**
     * set exception message from response
     *
     * @param string $response
     */
    public function setResponse($response)
    {
        $xml = simplexml_load_string($response);
        if (!empty($xml->error)) {
            $this->message = (string) $xml->error;
        }
    }
}
