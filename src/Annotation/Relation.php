<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Relation
{
    /**
     * @var array<string>
     *
     * @Required
     */
    public $path;

    /**
     * @var string
     *
     * @Required
     */
    public $targetEntity;

    /**
     * @var boolean
     */
    public $multiple = false;
}
