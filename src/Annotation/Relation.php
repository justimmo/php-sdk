<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Relation
{
    /**
     * @var string
     *
     * @Required
     */
    public $key;

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
