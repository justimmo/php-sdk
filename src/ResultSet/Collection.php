<?php

namespace Justimmo\Api\ResultSet;

class Collection extends \ArrayObject implements ResultSet
{
    /**
     * @inheritdoc
     */
    public function toKeyValue($keyGetter, $valueGetter)
    {
        $return = [];
        foreach ($this as $value) {
            if (!method_exists($value, $keyGetter)) {
                throw new \BadMethodCallException('Method ' . $keyGetter . ' not found on ' . get_class($value));
            }
            if (!method_exists($value, $valueGetter)) {
                throw new \BadMethodCallException('Method ' . $valueGetter . ' not found on ' . get_class($value));
            }
            $return[$value->$keyGetter()] = $value->$valueGetter();
        }

        return $return;
    }
}
