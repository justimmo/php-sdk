<?php

namespace Justimmo\Model\Mapper\V1;

use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Mapper\OrderMappingInterface;
use Justimmo\Model\Mapper\SupportedParametersInterface;

abstract class AbstractMapper implements MapperInterface, OrderMappingInterface, SupportedParametersInterface
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
        return $this->translate($this->getFilterMapping(), $modelPropertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderPropertyName(string $column): string
    {
        return $this->translate($this->getOrderMapping(), $column);
    }

    /**
     * Get the mapping of model property names to the columns the api sorts by
     *
     * Empty by default, so a mapper which has no sort columns of its own passes the name through,
     * exactly as the filter mapping does.
     *
     * @return array<string, string>
     */
    protected function getOrderMapping(): array
    {
        return [];
    }

    /**
     * Looks a model property name up in a mapping, case insensitively
     *
     * @param array<string, string> $mapping
     *
     * @return string the mapped name, or the name unchanged when the mapping has no entry
     */
    private function translate(array $mapping, string $modelPropertyName): string
    {
        $lower = mb_strtolower($modelPropertyName);

        foreach ($mapping as $key => $value) {
            if ($lower === mb_strtolower($key)) {
                return $value;
            }
        }

        return $modelPropertyName;
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, array<string, mixed>>
     */
    public function getSupportedFilters(string $call): array
    {
        return SupportedParameters::FILTERS[$call] ?? [];
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, array<string, mixed>>
     */
    public function getSupportedParameters(string $call): array
    {
        return SupportedParameters::PARAMETERS[$call] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecVersion(): string
    {
        return SupportedParameters::SPEC_VERSION;
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

        return array_key_exists($apiPropertyName, $mapping) ? $mapping[$apiPropertyName] : [];
    }
}
