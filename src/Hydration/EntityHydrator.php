<?php

namespace Justimmo\Api\Hydration;

use Doctrine\Common\Annotations\Reader;
use Justimmo\Api\Annotation\Column;
use Justimmo\Api\Annotation\Entity;
use Justimmo\Api\Annotation\Relation;

class EntityHydrator
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var \ReflectionClass[]
     */
    protected $reflectionClasses = [];

    /**
     * @var array
     */
    protected $instancePool = [];

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Creates a target entity of type $class with and populates the properties depending on $values
     *
     * @param array  $values
     * @param string $class
     *
     * @return object
     */
    public function hydrate(array $values, $class)
    {
        if (empty($values['id'])) {
            throw new \InvalidArgumentException("Values must have an id key.");
        }

        if (!empty($this->instancePool[$class][$values['id']])) {
            return $this->instancePool[$class][$values['id']];
        }

        $reflClass = $this->getReflectionClass($class);
        $instance  = $reflClass->newInstance();

        foreach ($reflClass->getProperties() as $reflProperty) {
            $value = $this->getValue($values, $reflProperty);
            if ($value !== null) {
                $reflProperty->setAccessible(true);
                $reflProperty->setValue($instance, $value);
            }
        }

        return $this->instancePool[$class][$values['id']] = $instance;
    }

    /**
     * @param array               $values
     * @param \ReflectionProperty $reflProperty
     *
     * @return mixed|null
     */
    protected function getValue(array $values, \ReflectionProperty $reflProperty)
    {
        $annotations = $this->reader->getPropertyAnnotations($reflProperty);
        if (empty($annotations)) {
            return null;
        }

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Column) {
                return $this->getValueFromColumnAnnotation($values, $annotation);
            }
            if ($annotation instanceof Relation) {
                return $this->getValueFromRelationAnnotation($values, $annotation);
            }
        }

        return null;
    }

    /**
     * @param array    $values
     * @param Relation $relation
     *
     * @return mixed|null
     */
    protected function getValueFromRelationAnnotation(array $values, Relation $relation)
    {
        $key = $relation->key;
        if (!array_key_exists($key, $values)) {
            return null;
        }

        if ($relation->multiple === false) {
            return $this->hydrate($values[$key], $relation->targetEntity);
        }

        $entities = [];
        foreach ($values[$key] as $entityRelationValues) {
            $entities[] = $this->hydrate($entityRelationValues, $relation->targetEntity);
        }

        return $entities;
    }

    /**
     * @param array  $values
     * @param Column $column
     *
     * @return mixed|null
     */
    protected function getValueFromColumnAnnotation(array $values, Column $column)
    {
        $key = $column->key;
        if (!array_key_exists($key, $values)) {
            return null;
        }

        return $this->castValue($values[$key], $column->type);
    }

    /**
     * Value casting into target type
     *
     * @param mixed  $value
     * @param string $type
     *
     * @return mixed
     */
    protected function castValue($value, $type)
    {
        switch ($type) {
            case 'string':
                return (string) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
        }

        return null;
    }

    /**
     * Returns a reflection of an entity class
     *
     * @param string $class
     *
     * @return \ReflectionClass
     */
    protected function getReflectionClass($class)
    {
        if (array_key_exists($class, $this->reflectionClasses)) {
            return $this->reflectionClasses[$class];
        }

        $reflClass   = new \ReflectionClass($class);
        $annotations = $this->reader->getClassAnnotations($reflClass);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Entity) {
                return $this->reflectionClasses[$class] = $reflClass;
            }
        }

        throw new \InvalidArgumentException($class . ' is missing annotation Justimmo\Api\Annotation\Entity.');
    }
}
