<?php

namespace Justimmo\Api\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Entity
{
    /**
     * @var string
     */
    public $cacheKey;
}
