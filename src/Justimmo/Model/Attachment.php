<?php

namespace Justimmo\Model;

use Justimmo\Exception\AttachmentSizeNotFoundException;

class Attachment
{
    protected $type;

    protected $extension;

    protected $title = null;

    protected $data = array();

    protected $pictureExtensions = array('jpg', 'gif', 'png', 'jpeg');

    protected $videoExtensions = array('avi', 'mp4', 'mpg', 'wmv');

    public function __construct($path, $type = null)
    {
        $this->extension = pathinfo($path, PATHINFO_EXTENSION);
        $this->data['orig'] = $path;
        $this->type = $type;

        if ($type === null) {
            $this->type = $this->determineTypeByExtension($this->extension);
        }
    }

    public function getUrl($size = 'orig')
    {
        if (!array_key_exists($size, $this->data)) {
            throw new AttachmentSizeNotFoundException('The size "' . $size . '" was not found');
        }

        return $this->data[$size];
    }

    /**
     * determines the type by extension
     *
     * @param $extension
     *
     * @return string
     */
    protected function determineTypeByExtension($extension)
    {
        if (in_array($extension, $this->pictureExtensions)) {
            return 'picture';
        } elseif (in_array($extension, $this->videoExtensions)) {
            return 'video';
        }
        return 'document';
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param mixed $extension
     *
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

}