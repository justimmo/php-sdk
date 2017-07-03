<?php

namespace Justimmo\Api\Exception;

class ClientException extends RequestException
{
    const DEFAULT_MESSAGE = 'The request contains not supported parameters.';

    /**
     * @var array
     */
    protected $validationErrors;

    /**
     * Thrown if resposne body does not contains a supported format
     *
     * @param string $message
     *
     * @return static
     */
    public static function invalidFormat($message = "Could not extract json content from response body.")
    {
        return new static($message, 400);
    }

    /**
     * Thrown on api 400 status code
     *
     * @param \Exception $previousException
     *
     * @return static
     */
    public static function invalidRequest(\Exception $previousException)
    {
        if (!($previousException instanceof \GuzzleHttp\Exception\RequestException)) {
            return new static(static::DEFAULT_MESSAGE, 400, $previousException);
        }

        $response = $previousException->getResponse();
        if ($response === null) {
            return new static(static::DEFAULT_MESSAGE, 400, $previousException);
        }

        $body = json_decode((string) $response->getBody(), true);
        $e    = new static(!empty($body['message']) ? $body['message'] : self::DEFAULT_MESSAGE, 400, $previousException);
        if (!empty($body['validationErrors'])) {
            $e->setValidationErrors($body['validationErrors']);
        }

        return $e;
    }

    /**
     * @param mixed $validationErrors
     *
     * @return ClientException
     */
    public function setValidationErrors(array $validationErrors)
    {
        $this->validationErrors = $validationErrors;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
