<?php

namespace Justimmo\Api\Entity\Traits;

trait Identifiable
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="id", type="string")
     */
    private $id;

    /**
     * @var string
     * @JUSTIMMO\Column(path="nextId", type="string")
     */
    private $nextId;

    /**
     * @var string
     * @JUSTIMMO\Column(path="previousId", type="string")
     */
    private $previousId;

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
    public function getNextId()
    {
        return $this->nextId;
    }

    /**
     * @return string
     */
    public function getPreviousId()
    {
        return $this->previousId;
    }
}
