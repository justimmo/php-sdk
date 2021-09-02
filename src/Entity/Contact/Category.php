<?php

namespace Justimmo\Api\Entity\Contact;

use Justimmo\Api\Annotation as JUSTIMMO;
use Justimmo\Api\Entity\Entity;
use Justimmo\Api\Entity\Identifiable;
use Justimmo\Api\Entity\Nameable;

/**
 * @JUSTIMMO\Entity(cacheKey="id")
 */
class Category implements Entity
{
    use Identifiable, Nameable;

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @var bool
     * @JUSTIMMO\Column(type="boolean")
     */
    private $marketing = false;

    /**
     * @var bool
     * @JUSTIMMO\Column(type="boolean")
     */
    private $newsletter = false;

    /**
     * @var string|null
     * @JUSTIMMO\Column(type="string")
     */
    private $newsletterLabel = null;

    /**
     * @return bool
     */
    public function isMarketing()
    {
        return $this->marketing;
    }

    /**
     * @return bool
     */
    public function isNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @return string|null
     */
    public function getNewsletterLabel()
    {
        return $this->newsletterLabel;
    }
}
