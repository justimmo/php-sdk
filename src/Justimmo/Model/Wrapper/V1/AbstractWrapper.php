<?php

namespace Justimmo\Model\Wrapper\V1;

use Justimmo\Model\Attachment;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Wrapper\WrapperInterface;

abstract class AbstractWrapper implements WrapperInterface
{
    /**
     * @var MapperInterface
     */
    protected $mapper;

    public function __construct(MapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * maps an mapping array between a SimpleXML and Objekt
     *
     * @param                   $mapping
     * @param \SimpleXMLElement $xml
     * @param                   $objekt
     */
    protected function map($mapping, \SimpleXMLElement $xml, $objekt)
    {
        foreach ($mapping as $key) {
            if (isset($xml->$key)) {
                $setter = $this->mapper->getSetter($key);
                $objekt->$setter($this->cast($xml->$key, $this->mapper->getType($key)));
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
        switch ($type) {
            case 'string':
                return trim((string) $xml);
            case 'int':
                return (int) $xml;
            case 'double':
                return (float) $xml;
            case 'boolean' :
                return (bool) trim((string) $xml);
            case 'datetime':
                $date = trim((string) $xml);
                if (empty($date)) {
                    return null;
                }
                return new \DateTime($date);
            default:
                return $xml;
        }
    }

    /**
     * trims the string values of an array
     *
     * The api pretty prints some responses, which puts the value of an element
     * on its own indented line. The resulting whitespace is part of the text
     * node, so an url read straight out of the array fails
     * filter_var(..., FILTER_VALIDATE_URL).
     *
     * @param array<string, mixed> $values
     *
     * @return array<string, mixed>
     */
    protected function trimValues(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = trim($value);
            }
        }

        return $values;
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

    /**
     * @param \SimpleXMLElement                                                       $xml
     * @param \Justimmo\Model\Realty|\Justimmo\Model\Employee|\Justimmo\Model\Project $attachmentAware
     * @param null                                                                    $type
     * @param null                                                                    $forceGroup
     *
     * @internal param array $data
     */
    protected function mapAttachmentGroup(\SimpleXMLElement $xml, $attachmentAware, $type = null, $forceGroup = null)
    {
        foreach ($xml as $anhang) {
            $data = $this->trimValues((array) $anhang->daten);
            $attributes = $this->attributesToArray($anhang);
            $group = $forceGroup ?: (array_key_exists('gruppe', $attributes) ? $attributes['gruppe'] : null);
            if (array_key_exists('pfad', $data)) {
                $path = isset($data['orig']) ? $data['orig'] : $data['pfad'];
                $attachment = new Attachment($path, $type, $group);
                $attachment->mergeData($data);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(array('vorschaubild' => $this->cast($anhang->vorschaubild)));
                }
                $attachment->setTitle($this->cast($anhang->anhangtitel));
                if (isset($anhang->anhang_beschreibung)) {
                    $attachment->setDescription($this->cast($anhang->anhang_beschreibung));
                }
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            } elseif (isset($anhang->pfad)) {
                if (isset($anhang->gruppe)) {
                    $group = strtoupper($this->cast($anhang->gruppe));
                }
                $path = isset($anhang->orig) ? $anhang->orig : $anhang->pfad;
                $attachment = new Attachment($this->cast($path), $type, $group);
                if (isset($anhang->vorschaubild)) {
                    $attachment->mergeData(array('vorschaubild' => $this->cast($anhang->vorschaubild)));
                }
                $attachment->setTitle($this->cast($anhang->titel));
                if (isset($anhang->anhang_beschreibung)) {
                    $attachment->setDescription($this->cast($anhang->anhang_beschreibung));
                }
                $attachment->setOriginalFilename($this->cast($anhang->original_dateiname));
                $attachmentAware->addAttachment($attachment);
            }
        }
    }

}
