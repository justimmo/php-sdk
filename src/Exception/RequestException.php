<?php

namespace Justimmo\Api\Exception;


class RequestException extends \Exception implements JustimmoApiException
{
    /**
     * Creates a RequestException from another Exception
     *
     * @param \Exception $e
     *
     * @return \Exception|static
     */
    public static function createFromException(\Exception $e)
    {
        if ($e instanceof AuthorizationException) {
            return $e;
        }

        if ($e->getCode() == 404) {
            return NotFoundException::notFound($e);
        }

        if (in_array($e->getCode(), [401, 403])) {
            return AuthorizationException::accessDenied($e);
        }

        if ($e->getCode() == 400) {
            return ClientException::invalidRequest($e);
        }

        if ($e->getCode() >= 500) {
            return ServerException::internalServerError($e);
        }

        return new static($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * Returns HTTP Status Code
     *
     * @return int|mixed
     */
    public function getHttpStatusCode()
    {
        return $this->getCode();
    }
}
