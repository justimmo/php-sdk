<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Favicon implements Entity
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="url", type="string")
     */
    private string $url;

    /**
     * @var string
     * @JUSTIMMO\Column(path="mimeType", type="string")
     */
    private string $mimeType;


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

}
