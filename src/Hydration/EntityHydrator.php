<?php

namespace Justimmo\Api\Hydration;

use Doctrine\Common\Annotations\Reader;
use Justimmo\Api\Annotation\Column;
use Justimmo\Api\Annotation\Entity;
use Justimmo\Api\Annotation\PreHydrate;
use Justimmo\Api\Annotation\Relation;
use Justimmo\Api\ResultSet\Collection;

class EntityHydrator
{
    /**
     * @var array
     */
    protected $instancePool = [];

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
    protected $propertyAnnotations = [];

    /**
     * @var array
     */
    protected $classAnnotations = [];

    /**
     * @param Reader     $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader       = $reader;
    }

    /**
     * Creates a target entity of type $class with and populates the properties depending on $values
     *
     * @param array  $values
     * @param string $class
     *
     * @return \Justimmo\Api\Entity\Entity
     */
    public function hydrate(array $values, $class)
    {
        $reflClass = $this->getReflectionClass($class);
        $class     = $reflClass->getName();
        $cacheable = false;
        $cacheKey  = null;

        if (!empty($this->classAnnotations[$class][Entity::class])) {
            /** @var Entity $annotation */
            $annotation = $this->classAnnotations[$class][Entity::class];

            if (!empty($annotation->cacheKey)
                && !empty($values[$annotation->cacheKey])
            ) {
                $cacheable = true;
                $cacheKey  = $class . ':' . $values[$annotation->cacheKey];
            }
        }

        if ($cacheable && array_key_exists($cacheKey, $this->instancePool)) {
            return $this->instancePool[$cacheKey];
        }

        if (!empty($this->classAnnotations[$class][PreHydrate::class])) {
            $values = $this->preHydrate($values, $this->classAnnotations[$class][PreHydrate::class]);
        }

        /** @var \Justimmo\Api\Entity\Entity $instance */
        $instance = $reflClass->newInstance();

        foreach ($reflClass->getProperties() as $reflProperty) {
            $value = $this->getValue($values, $class, $reflProperty->getName());
            if ($value !== null) {
                $reflProperty->setAccessible(true);
                $reflProperty->setValue($instance, $value);
            }
        }

        if ($cacheable) {
            $this->instancePool[$cacheKey] = $instance;
        }

        return $instance;
    }

    /**
     * Iterates over values and possibly changes them depending on annotations
     *
     * @param array      $values
     * @param PreHydrate $annotation
     *
     * @return array
     */
    protected function preHydrate(array $values, PreHydrate $annotation)
    {
        if (empty($annotation->moveTo)) {
            return $values;
        }

        foreach ($annotation->moveTo as $moveable => $moveTo) {
            if (!array_key_exists($moveable, $values)) {
                continue;
            }

            $values[$moveTo][$moveable] = $values[$moveable];
            unset($values[$moveable]);
        }

        return $values;
    }

    /**
     * @param array  $values
     * @param string $property
     * @param string $class
     *
     * @return mixed|null
     */
    protected function getValue(array $values, $class, $property)
    {
        /** @var Column|Relation $annotation */
        if (!empty($this->propertyAnnotations[$class][$property][Column::class])) {
            $annotation = $this->propertyAnnotations[$class][$property][Column::class];
        } elseif (!empty($this->propertyAnnotations[$class][$property][Relation::class])) {
            $annotation = $this->propertyAnnotations[$class][$property][Relation::class];
        } else {
            return null;
        }

        $path  = !empty($annotation->path) ? $annotation->path : $property;
        if (!array_key_exists($path, $values)) {
            return null;
        }


        return $annotation instanceof Column
            ? $this->getValueFromColumnAnnotation($values[$path], $annotation)
            : $this->getValueFromRelationAnnotation($values[$path], $annotation);
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

        while ($key = array_shift($path)) {
            if (!array_key_exists($key, $values)) {
                return null;
            }

            $values = $values[$key];
        }

        return $values;
    }

    /**
     * Resolve a Column annotation by by recursively calling the cast value
     *
     * @param array  $values
     * @param Column $annotation
     *
     * @return mixed|null
     */
    protected function getValueFromColumnAnnotation($values, Column $annotation)
    {
        if ($annotation->multiple === false) {
            return $this->castValue($values, $annotation->type);
        }

        if (!is_array($values)) {
            return [$this->castValue($values, $annotation->type)];
        }

        $entities = [];
        foreach ($values as $value) {
            $entities[] = $this->castValue($value, $annotation->type);
        }

        return $entities;
    }

    /**
     * Resolve a Relation annotation by by recursively calling the hydrate action
     *
     * @param array    $values
     * @param Relation $annotation
     *
     * @return mixed|\Justimmo\Api\ResultSet\ResultSet
     */
    protected function getValueFromRelationAnnotation(array $values, Relation $annotation)
    {
        if ($annotation->multiple === false) {
            return $this->hydrate($values, $annotation->targetEntity);
        }

        $entities = [];
        foreach ($values as $entityRelationValues) {
            if (!is_array($entityRelationValues)) {
                throw new \InvalidArgumentException("Argument 1 passed to " . __METHOD__ . " must be of the type array.");
            }
            $entities[] = $this->hydrate($entityRelationValues, $annotation->targetEntity);
        }

        return !empty($entities) ? new Collection($entities) : [];
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
        if ($type == 'original') {
            return $value;
        }

        if (is_array($value)) {
            throw new \InvalidArgumentException('Argument 1 passed to ' . __METHOD__ . ' must be a scalar type.');
        }

        switch ($type) {
            case 'date':
                return new \DateTime($value);
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
        if (strpos($class, '\\') === 0) {
            $class = substr($class, 1);
        }

        if (array_key_exists($class, $this->reflectionClasses)) {
            return $this->reflectionClasses[$class];
        }

        $reflClass  = new \ReflectionClass($class);
        $annotation = $this->reader->getClassAnnotation($reflClass, 'Justimmo\Api\Annotation\Entity');
        if (empty($annotation)) {
            throw new \InvalidArgumentException($class . ' is missing annotation Justimmo\Api\Annotation\Entity.');
        }

        foreach ($this->reader->getClassAnnotations($reflClass) as $annotation) {
            $this->classAnnotations[$reflClass->getName()][get_class($annotation)] = $annotation;
        }

        foreach ($reflClass->getProperties() as $reflProperty) {
            foreach ($this->reader->getPropertyAnnotations($reflProperty) as $annotation) {
                $this->propertyAnnotations[$reflClass->getName()][$reflProperty->getName()][get_class($annotation)] = $annotation;
            }
        }

        return $this->reflectionClasses[$class] = $reflClass;
    }
}
