<?php

namespace Justimmo\Api\Entity\Attachment;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Collection
 */
class AttachmentCollection implements \IteratorAggregate
{
    /**
     * @var Attachment[]
     * @JUSTIMMO\Relation(targetEntity="\Justimmo\Api\Entity\Attachment\Attachment", multiple=true)
     */
    private $attachments = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->attachments);
    }

    /**
     * Returns attachments of group pictures
     *
     * @return Attachment[]
     */
    public function getPictures()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_PICTURES);
    }

    /**
     * Returns attachments of group plans
     *
     * @return Attachment[]
     */
    public function getPlans()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_PLANS);
    }

    /**
     * Returns attachments of group videos
     *
     * @return Attachment[]
     */
    public function getVideos()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_VIDEOS);
    }

    /**
     * Returns attachments of group documents
     *
     * @return Attachment[]
     */
    public function getDocuments()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_DOCUMENTS);
    }

    /**
     * Returns attachments of group three60 pictures
     *
     * @return Attachment[]
     */
    public function getThree60Pictures()
    {
        return $this->filterAttachmentsByGroup(Attachment::GROUP_THREE60_PICTURES);
    }

    /**
     * Filters attachments by a specific group
     *
     * @param array|int $groups
     *
     * @return Attachment[]
     */
    private function filterAttachmentsByGroup($groups)
    {
        if (!is_array($groups)) {
            $groups = [ $groups ];
        }

        $groupedAttachments = [];
        foreach ($this->attachments as $attachment) {
            if (in_array($attachment->getGroup(), $groups)) {
                $groupedAttachments[] = $attachment;
            }
        }

        return $groupedAttachments;
    }
}
