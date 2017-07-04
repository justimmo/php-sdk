<?php

namespace Justimmo\Api\Entity;

use Justimmo\Api\Annotation as JUSTIMMO;

/**
 * @JUSTIMMO\Entity()
 */
abstract class BaseEntity implements Entity
{
    /**
     * @var string
     * @JUSTIMMO\Column(path="id", type="string")
     */
    protected $id;

    /**
     * @var string
     * @JUSTIMMO\Column(path="name")
     */
    protected $name;

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
    public function getName()
    {
        return $this->name;
    }
}
