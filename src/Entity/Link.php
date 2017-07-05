<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
class Link implements Entity
{
    const TYPE_LINK       = 'links';
    const TYPE_MOVIE_LINK = 'filmlink';

    /**
     * @var string
     * @JUSTIMMO\Column(path="id", type="string")
     */
    private $id;

    /**
     * @var string
     * @JUSTIMMO\Column(path="url", type="string")
     */
    private $url;

    /**
     * @var string
     * @JUSTIMMO\Column(path="type", type="string")
     */
    private $type;

    /**
     * @var string
     * @JUSTIMMO\Column(path="description", type="string")
     */
    private $description;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isMovieLink()
    {
        return $this->type === self::TYPE_MOVIE_LINK;
    }

    /**
     * @return bool
     */
    public function isLink()
    {
        return $this->type === self::TYPE_LINK;
    }
}
