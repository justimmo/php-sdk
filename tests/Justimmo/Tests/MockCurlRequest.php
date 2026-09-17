<?php

namespace Justimmo\Tests;

use Justimmo\Curl\CurlRequestInterface;

class MockCurlRequest implements CurlRequestInterface
{
    protected int $statusCode = 200;

    protected ?string $content;

    protected ?string $contentType;

    /**
     * MockCurlRequest constructor.
     *
     * @param string|null $content     raw response body
     * @param int         $statusCode
     * @param string|null $contentType the list and detail endpoints answer in xml, the ids endpoints in json
     */
    public function __construct(?string $content, int $statusCode, ?string $contentType = 'text/xml; charset=UTF-8')
    {
        $this->content     = $content;
        $this->statusCode  = $statusCode;
        $this->contentType = $contentType;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getError(): ?string
    {
        return null;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setOption($key, $value): void
    {
    }

    public function setParameters(mixed $parameters = []): void
    {
    }

    public function get(): mixed
    {
        return null;
    }

    public function post(mixed $parameters = null): mixed
    {
        return null;
    }
}
