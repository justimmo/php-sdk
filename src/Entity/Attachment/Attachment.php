<?php

namespace Justimmo\Api\Entity\Attachment;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Traits\Identifiable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Attachment implements Entity
{
    use Identifiable;

    const TYPE_PICTURE  = 'pic';
    const TYPE_VIDEO    = 'video';
    const TYPE_DOCUMENT = 'doc';

    const GROUP_PICTURES = [
        1,
        10,
        12,
        22,
        23,
    ];

    const GROUP_PLANS = [
        2,
        13,
        24,
    ];

    const GROUP_VIDEOS = [
        3,
        14,
    ];

    const GROUP_DOCUMENTS = [
        4,
        11,
        15,
        25,
    ];

    const GROUP_THREE60_PICTURES = [
        7,
        18,
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
    public function isGroupPictures()
    {
        return in_array($this->group, self::GROUP_PICTURES);
    }

    /**
     * @return bool
     */
    public function isGroupPlans()
    {
        return in_array($this->group, self::GROUP_PLANS);
    }

    /**
     * @return bool
     */
    public function isGroupVideos()
    {
        return in_array($this->group, self::GROUP_VIDEOS);
    }

    /**
     * @return bool
     */
    public function isGroupDocuments()
    {
        return in_array($this->group, self::GROUP_DOCUMENTS);
    }

    /**
     * @return bool
     */
    public function isGroupThreeSixty()
    {
        return in_array($this->group, self::GROUP_THREE60_PICTURES);
    }
}
