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

    const TYPE_LINK      = 'links';
    const TYPE_MOVIE     = 'filmlink';
    const TYPE_TOUR      = 'rundgang';
    const TYPE_PROJECT   = 'projekturl';
    const TYPE_FACEBOOK  = 'facebook';
    const TYPE_TWITTER   = 'twitter';
    const TYPE_XING      = 'xing';
    const TYPE_LINKEDIN  = 'linkedin';
    const TYPE_INSTAGRAM = 'instagram';

    const SOCIAL_NETWORKS = [
        self::TYPE_FACEBOOK,
        self::TYPE_TWITTER,
        self::TYPE_XING,
        self::TYPE_LINKEDIN,
        self::TYPE_INSTAGRAM,
    ];

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

    public function __toString()
    {
        return (string) $this->getDescription();
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
    public function isTour()
    {
        return $this->type === self::TYPE_TOUR;
    }

    /**
     * @return bool
     */
    public function isMovie()
    {
        return $this->type === self::TYPE_MOVIE;
    }

    /**
     * @return bool
     */
    public function isLink()
    {
        return $this->type === self::TYPE_LINK;
    }

    /**
     * @return bool
     */
    public function isFacebook()
    {
        return $this->type === self::TYPE_FACEBOOK;
    }

    /**
     * @return bool
     */
    public function isTwitter()
    {
        return $this->type === self::TYPE_TWITTER;
    }

    /**
     * @return bool
     */
    public function isXing()
    {
        return $this->type === self::TYPE_XING;
    }

    /**
     * @return bool
     */
    public function isLinkedIn()
    {
        return $this->type === self::TYPE_LINKEDIN;
    }

    /**
     * @return bool
     */
    public function isInstagram()
    {
        return $this->type === self::TYPE_INSTAGRAM;
    }

    /**
     * Returns if the link represents a link to a social network
     *
     * @return bool
     */
    public function isSocialNetwork()
    {
        return in_array($this->type, self::SOCIAL_NETWORKS);
    }

    /**
     * @return bool
     */
    public function isProject()
    {
        return $this->type === self::TYPE_PROJECT;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
