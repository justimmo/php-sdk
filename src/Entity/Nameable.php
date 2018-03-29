<?php

namespace Justimmo\Api\Entity;

trait Nameable
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="name")
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
