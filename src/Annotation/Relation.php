<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Relation
{
    /**
     * Defaults to property name if not set
     *
     * @var string
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
