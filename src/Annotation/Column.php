<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Column
{
    /**
     * @var string
     *
     * @Enum({"string", "integer", "float", "boolean"})
     */
    public $type = 'string';

    /**
     * @var string
     *
     * @Required
     */
    public $key;

    /**
     * @var array<string>
     */
    public $path = [];
}
