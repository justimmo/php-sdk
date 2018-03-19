<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Delegated
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
     * @var string
     *
     * @Required
     */
    public $targetPath;
}
