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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
