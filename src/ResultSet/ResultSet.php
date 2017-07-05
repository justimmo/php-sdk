<?php

namespace Justimmo\Api\ResultSet;

interface ResultSet extends \Countable, \Traversable, \ArrayAccess, \IteratorAggregate
{
    /**
     * Converts the result set into a key/value store array
     *
     * @param string $keyGetter
     * @param string $valueGetter
     *
     * @return array
     */
    public function toKeyValue($keyGetter, $valueGetter);
}
