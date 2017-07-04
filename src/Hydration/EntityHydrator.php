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
        /** @var Column|Relation $annotation */
        $annotation = $this->reader->getPropertyAnnotation($reflProperty, 'Justimmo\Api\Annotation\Column');
        if ($annotation === null) {
            $annotation = $this->reader->getPropertyAnnotation($reflProperty, 'Justimmo\Api\Annotation\Relation');
        }
        if ($annotation === null) {
            return null;
        }

        $value = $this->extractValueFromPath($values, $annotation->path);
        if ($value === null) {
            return null;
        }

        return $annotation instanceof Column
            ? $this->castValue($value, $annotation->type)
            : $this->getValueFromRelationAnnotation($value, $annotation);
    }

    /**
     * Recursively fetch a value from a multidimensional array depending on a given path
     *
     * @param array $values
     * @param array $path
     *
     * @return mixed
     */
    protected function extractValueFromPath(array $values, array $path)
    {
        if (empty($path)) {
            return null;
        }

        $key = array_shift($path);
        if (!array_key_exists($key, $values)) {
            return null;
        }

        return (empty($path))
            ? $values[$key]
            : $this->extractValueFromPath($values[$key], $path);
    }

    /**
     * Resolve a Relation annotation by by recursively calling the hydrate action
     *
     * @param array    $values
     * @param Relation $annotation
     *
     * @return mixed|null
     */
    protected function getValueFromRelationAnnotation(array $values, Relation $annotation)
    {
        if ($annotation->multiple === false) {
            return $this->hydrate($values, $annotation->targetEntity);
        }

        $entities = [];
        foreach ($values as $entityRelationValues) {
            if (!is_array($entityRelationValues)) {
                throw new \InvalidArgumentException("Argument 1 passed to " . __CLASS__ . "::hydrate must be of the type array.");
            }
            $entities[] = $this->hydrate($entityRelationValues, $annotation->targetEntity);
        }

        return $entities;
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
        if (is_array($value)) {
            return null;
        }

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
