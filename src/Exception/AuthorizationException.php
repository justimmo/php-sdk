<?php

namespace Justimmo\Api\Exception;

class AuthorizationException extends RequestException
{
    /**
     * Thrown when token could not be fetched from auth server
     *
     * @param \Exception $previous
     *
     * @return static
     */
    public static function invalidToken(\Exception $previous = null)
    {
        return new static('Could not retrieve a valid access token.', 401, $previous);
    }

    /**
     * Thrown when api does not accept provided token
     *
     * @param \Exception $previous
     *
     * @return static
     */
    public static function accessDenied(\Exception $previous = null)
    {
        return new static('The api denied access to your provided token.', 401, $previous);
    }
}
