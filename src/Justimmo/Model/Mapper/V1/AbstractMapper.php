<?php

namespace Justimmo\Model\Mapper\V1;

use Justimmo\Model\Mapper\MapperInterface;

abstract class AbstractMapper implements MapperInterface
{
    /**
     * get the mapping array
     *
     * @return array
     */
    abstract protected function getMapping();

    /**
     * get mapping for filter
     *
     * @return array
     */
    abstract protected function getFilterMapping();

    /**
     * gets the setter for a api property name
     *
     * @param $apiPropertyName
     *
     * @return mixed
     */
    public function getSetter($apiPropertyName)
    {
        $property = $this->getProperty($apiPropertyName);

        return 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }

    /**
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getType($apiPropertyName)
    {
        $values = $this->getValues($apiPropertyName);

        return array_key_exists('type', $values) ? $values['type'] : 'string';
    }

    /**
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getProperty($apiPropertyName)
    {
        $values = $this->getValues($apiPropertyName);

        return array_key_exists('property', $values) ? $values['property'] : $apiPropertyName;
    }

    public function getFilterPropertyName($modelPropertyName)
    {
        $lowerModelPropertyName = mb_strtolower($modelPropertyName);
        $mapping = $this->getFilterMapping();
        foreach ($mapping as $key => $value) {
            if ($lowerModelPropertyName == mb_strtolower($key)) {
                return $value;
            }
        }

        return $modelPropertyName;
    }

    /**
     * get mapping information of a api property
     *
     * @param $apiPropertyName
     *
     * @return array
     */
    protected function getValues($apiPropertyName)
    {
        $mapping = $this->getMapping();

        return array_key_exists($apiPropertyName, $mapping) ? $mapping[$apiPropertyName] : array();
    }
}
