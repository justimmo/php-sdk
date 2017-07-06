<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class PreHydrate
{
    /**
     * Associative to move values into other values
     *
     * @var array
     */
    public $moveTo = [];
}
