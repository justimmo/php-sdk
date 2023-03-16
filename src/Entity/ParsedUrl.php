<?php
declare(strict_types=1);

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class ParsedUrl implements Entity
{
    /**
     * @JUSTIMMO\Column(path="scheme", type="string")
     */
    private ?string $scheme;

    /**
     * @JUSTIMMO\Column(path="host", type="string")
     */
    private ?string $host;

    /**
     * @JUSTIMMO\Column(path="port", type="string")
     */
    private ?string $port;

    /**
     * @JUSTIMMO\Column(path="path", type="string")
     */
    private ?string $path;

    /**
     * @JUSTIMMO\Column(path="query", type="string")
     */
    private ?string $query;

    /**
     * @JUSTIMMO\Column(path="fragment", type="string")
     */
    private ?string $fragment;


    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

}
