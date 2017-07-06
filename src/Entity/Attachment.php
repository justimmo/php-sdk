<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Traits\Identifiable;

/**
 * @JUSTIMMO\Entity()
 */
class Attachment implements Entity
{
    use Identifiable;

    const TYPE_PICTURE  = 'pic';
    const TYPE_VIDEO    = 'video';
    const TYPE_DOCUMENT = 'doc';

    const GROUP_PICTURES         = 1;
    const GROUP_PLANS            = 2;
    const GROUP_VIDEOS           = 3;
    const GROUP_DOCUMENTS        = 4;
    const GROUP_THREE60_PICTURES = 7;

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
     * @JUSTIMMO\Column(path="filename", type="string")
     */
    private $filename;

    /**
     * @var string
     * @JUSTIMMO\Column(path="title", type="string")
     */
    private $title;

    /**
     * @var string
     * @JUSTIMMO\Column(path="description", type="string")
     */
    private $description;

    /**
     * @var int
     * @JUSTIMMO\Column(path="group", type="integer")
     */
    private $group;

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
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the url to a specific picture config (size, branding, etc...)
     *
     * @param string $config
     *
     * @return string
     */
    public function getUrlForConfig($config = 'orig')
    {
        return preg_replace("!\/(pic|video)\/(\w+)\/!", "/$1/" . $config . "/", $this->getUrl());
    }

    /**
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return bool
     */
    public function isTypePicture()
    {
        return $this->type === self::TYPE_PICTURE;
    }

    /**
     * @return bool
     */
    public function isTypeDocument()
    {
        return $this->type === self::TYPE_DOCUMENT;
    }

    /**
     * @return bool
     */
    public function isTypeVideo()
    {
        return $this->type === self::TYPE_VIDEO;
    }

    /**
     * @return bool
     */
    public function isGroupPlan()
    {
        return $this->group = self::GROUP_PLANS;
    }

    /**
     * @return bool
     */
    public function isGroupThreeSixty()
    {
        return $this->group = self::GROUP_THREE60_PICTURES;
    }
}
