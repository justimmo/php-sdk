<?php

namespace Justimmo\Model\Mapper;

class NullMapper implements MapperInterface
{
    /**
     * gets the setter for a api property name
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getSetter($apiPropertyName)
    {
        return 'set' . ucfirst($apiPropertyName);
    }

    /**
     * get the type a property has to be cast ing
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getType($apiPropertyName)
    {
        return $apiPropertyName;
    }

    /**
     * get the name of the property on a model object for a api property name
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getProperty($apiPropertyName)
    {
        return $apiPropertyName;
    }

    /**
     * gets a filter property name to sent to the api call for a specific model property
     *
     * @param $modelPropertyName
     *
     * @return string
     */
    public function getFilterPropertyName($modelPropertyName)
    {
        return $modelPropertyName;
    }
}
