<?php

namespace Justimmo\Model\Mapper;

interface MapperInterface
{
    /**
     * gets the setter for a api property name
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getSetter($apiPropertyName);

    /**
     * get the type a property has to be cast ing
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getType($apiPropertyName);

    /**
     * get the name of the property on a model object for a api property name
     *
     * @param $apiPropertyName
     *
     * @return string
     */
    public function getProperty($apiPropertyName);
}
