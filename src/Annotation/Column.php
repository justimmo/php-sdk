<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Column
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
     * @Enum({"string", "integer", "float", "boolean", "date", "original"})
     */
    public $type = 'string';

    /**
     * @var bool
     */
    public $multiple = false;
}
