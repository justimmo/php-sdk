<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Wrapper\WrapperInterface;


abstract class AbstractWrapper implements WrapperInterface
{
    /**
     * maps an mapping array between a SimpleXML and Objekt
     *
     * @param                   $mapping
     * @param \SimpleXMLElement $xml
     * @param                   $objekt
     */
    protected function map($mapping, \SimpleXMLElement $xml, $objekt)
    {
        foreach ($mapping as $key => $cast) {
            if (isset($xml->$key)) {
                if (is_array($cast) && array_key_exists('setter', $cast)) {
                    $setter = $cast['setter'];
                    $objekt->$setter($this->cast($xml->$key, $cast['type']));
                } else {
                    $setter = $this->buildSetter($key);
                    $objekt->$setter($this->cast($xml->$key, $cast));
                }
            }
        }
    }

    /**
     * casts a simple xml element to a type
     *
     * @param        $xml
     * @param string $type
     *
     * @return float|int|null|string|\DateTime
     */
    protected function cast(\SimpleXMLElement $xml, $type = 'string')
    {
        if ($xml->count() == 0) {
            return null;
        }

        switch ($type) {
            case 'string':
                return (string) $xml;
            case 'int':
                return (int) $xml;
            case 'double':
                return (double) $xml;
            case 'datetime':
                return new \DateTime((string) $xml);
            default:
                return $xml;
        }
    }

    /**
     * build a setter
     *
     * @param $key
     *
     * @return string
     */
    protected function buildSetter($key)
    {
        return 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    }

    /**
     * converts the attributes of a SimpleXmlElement to an array
     *
     * @param \SimpleXMLElement $xml
     *
     * @return array
     */
    protected function attributesToArray(\SimpleXMLElement $xml)
    {
        $array = (array) $xml;

        return array_key_exists('@attributes', $array) ? $array['@attributes'] : array();
    }


}