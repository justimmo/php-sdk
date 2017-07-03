<?php

namespace Justimmo\Api\Exception;

class ServerException extends RequestException
{
    /**
     * Thrown if api delivers >= 500 response code.
     *
     * @param \Exception $e
     *
     * @return static
     */
    public static function internalServerError(\Exception $e)
    {
        return new static('Internal Server Error', $e->getCode(), $e);
    }
}
