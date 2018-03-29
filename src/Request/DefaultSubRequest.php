<?php

namespace Justimmo\Api\Request;

/**
 * @property array $query
 * @property array $fields
 */
trait DefaultSubRequest
{
    /**
     * @inheritdoc
     */
    public function getSubFilters()
    {
        return array_key_exists('f', $this->query) ? $this->query['f'] : [];
    }

    /**
     * @inheritdoc
     */
    public function getSubFields()
    {
        return $this->fields;
    }
}
