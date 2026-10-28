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
     * @param string $apiPropertyName
     *
     * @return mixed
     */
    public function getSetter($apiPropertyName)
    {
        $property = $this->getProperty($apiPropertyName);

        return 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }

    /**
     * @param string $apiPropertyName
     *
     * @return string
     */
    public function getType($apiPropertyName)
    {
        $values = $this->getValues($apiPropertyName);

        return array_key_exists('type', $values) ? $values['type'] : 'string';
    }

    /**
     * @param string $apiPropertyName
     *
     * @return string
     */
    public function getProperty($apiPropertyName)
    {
        $values = $this->getValues($apiPropertyName);

        return array_key_exists('property', $values) ? $values['property'] : $apiPropertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterPropertyName($modelPropertyName)
    {
        return $this->translate($this->getFilterMapping(), $modelPropertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderPropertyName(string $column): string
    {
        $sort = $this->translate($this->getOrderMapping(), $column);

        // a name which sorting spells differently wins. Every other one is translated as a filter
        // name through the public method, as before 1.5.0, so a subclass which replaced it keeps
        // sorting by its own names
        if ($sort !== $this->translate($this->getFilterMapping(), $column)) {
            return $sort;
        }

        return $this->getFilterPropertyName($column);
    }

    /**
     * Get the mapping of model property names to the columns the api sorts by
     *
     * This returns the filter mapping by default, because the two agree for almost every name and
     * a mapper written outside the SDK has only ever had the one table. A mapper overrides it to
     * declare the names where sorting differs, and those win.
     *
     * @return array<string, string>
     */
    protected function getOrderMapping(): array
    {
        return $this->getFilterMapping();
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
