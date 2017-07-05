<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Column
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
     * @Enum({"string", "integer", "float", "boolean"})
     */
    public $type = 'string';

    /**
     * @var bool
     */
    public $multiple = false;
}
