<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Link implements Entity
{
    use Identifiable;

    const TYPE_LINK       = 'links';
    const TYPE_MOVIE_LINK = 'filmlink';
    const TYPE_TOUR_LINK  = 'rundgang';

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
    public function isTourLink()
    {
        return $this->type === self::TYPE_TOUR_LINK;
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

    public function __toString()
    {
        return (string) $this->getDescription();
    }
}