<?php

namespace Justimmo\Exception;

use Exception;

/**
 * Class InvalidRequestException
 *
 * thrown when the client request is invalid, status code 400-499
 *
 * @package Justimmo\Exception
 */
class InvalidRequestException extends Exception implements JustimmoException
{
    /**
     * set the exception message from response
     *
     * @param string|null $response    raw response body, null when curl produced no content
     * @param string|null $contentType content type of the response, decides how the body is parsed
     */
    public function setResponse(?string $response, ?string $contentType = null): void
    {
        $data = $this->decodeResponse($response, $contentType);
        if ($data === null) {
            return;
        }

        $violations = $this->readViolations($data);

        $message = $this->readMessage($data, $violations !== []);
        if ($message !== '') {
            $this->message = $message;
        }

        $this->handleViolations($violations);
    }

    /**
     * Hook for subclasses which are interested in the single validation failures
     *
     * @param array<int, array{propertyPath: string, message: string}> $violations
     */
    protected function handleViolations(array $violations): void
    {
    }

    /**
     * Decodes the response body into an array, regardless of the format it came in
     *
     * @param string|null $response
     * @param string|null $contentType
     *
     * @return array<string, mixed>|null null if there is no body or it cannot be decoded
     */
    private function decodeResponse(?string $response, ?string $contentType): ?array
    {
        if ($response === null || trim($response) === '') {
            return null;
        }

        if ($this->isJsonResponse($response, $contentType)) {
            $data = json_decode($response, true);

            return is_array($data) ? $data : null;
        }

        return $this->xmlToArray($response);
    }

    /**
     * The ids endpoints answer in JSON, list and detail endpoints in XML, so the format is
     * taken from the content type of the response and only guessed if that one is missing
     *
     * @param string      $response
     * @param string|null $contentType
     *
     * @return bool
     */
    private function isJsonResponse(string $response, ?string $contentType): bool
    {
        if ($contentType !== null) {
            $contentType = strtolower($contentType);

            if (str_contains($contentType, 'json')) {
                return true;
            }

            if (str_contains($contentType, 'xml')) {
                return false;
            }
        }

        return str_starts_with(ltrim($response), '{');
    }

    /**
     * Brings an XML body into the same shape a JSON body is decoded to
     *
     * @param string $response
     *
     * @return array<string, mixed>|null
     */
    private function xmlToArray(string $response): ?array
    {
        $xml = @simplexml_load_string($response);
        if ($xml === false) {
            return null;
        }

        $violations = [];
        foreach ($xml->violations as $violation) {
            $violations[] = [
                'propertyPath' => (string) $violation->propertyPath,
                'title'        => (string) $violation->title,
            ];
        }

        return [
            'error'      => (string) $xml->error,
            'detail'     => (string) $xml->detail,
            'title'      => (string) $xml->title,
            'violations' => $violations,
        ];
    }

    /**
     * The message of the api, localized and only meant to be read by humans.
     *
     * The problem details of a validation error name the offending input, so they say more than
     * the message the SDK built. A generic 404 or 401 body does not: its title is "An error
     * occurred" and its detail "Not Found", so there the message of the SDK is kept.
     *
     * @param array<string, mixed> $data
     * @param bool                 $hasViolations whether the body carries validation failures
     *
     * @return string
     */
    private function readMessage(array $data, bool $hasViolations): string
    {
        // legacy endpoints answer with <justimmo><error>...</error></justimmo>
        $error = $this->readString($data, 'error');
        if ($error !== '') {
            return $error;
        }

        if (!$hasViolations) {
            return '';
        }

        // symfony problem details of a validation error
        foreach (['detail', 'title'] as $key) {
            $message = $this->readString($data, $key);

            if ($message !== '') {
                return $message;
            }
        }

        return '';
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<int, array{propertyPath: string, message: string}>
     */
    private function readViolations(array $data): array
    {
        if (!isset($data['violations']) || !is_array($data['violations'])) {
            return [];
        }

        $violations = [];
        foreach ($data['violations'] as $violation) {
            if (!is_array($violation)) {
                continue;
            }

            $propertyPath = $this->readString($violation, 'propertyPath');
            $message      = $this->readString($violation, 'title');

            if ($propertyPath === '' && $message === '') {
                continue;
            }

            $violations[] = [
                'propertyPath' => $propertyPath,
                'message'      => $message,
            ];
        }

        return $violations;
    }

    /**
     * @param array<mixed> $data
     * @param string       $key
     *
     * @return string the trimmed value, empty string if it is missing or not a string
     */
    private function readString(array $data, string $key): string
    {
        return isset($data[$key]) && is_string($data[$key]) ? trim($data[$key]) : '';
    }
}
