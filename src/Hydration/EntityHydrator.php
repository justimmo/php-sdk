<?php

namespace Justimmo\Api\Hydration;

use Doctrine\Common\Annotations\Reader;
use Justimmo\Api\Annotation\Column;
use Justimmo\Api\Annotation\Delegated;
use Justimmo\Api\Annotation\Entity;
use Justimmo\Api\Annotation\PreHydrate;
use Justimmo\Api\Annotation\Relation;
use Justimmo\Api\ResultSet\Collection;
use Justimmo\Api\Annotation\Collection as CollectionAnnotation;

/**
 * Class EntityHydrator
 * @todo - rework internal caching
 */
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
    protected $reflectionProperties = [];

    /**
     * @var array
     */
    protected $classAnnotations = [];

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
     * @return \Justimmo\Api\Entity\Entity
     */
    public function hydrate(array $values, $class)
    {
        $reflClass = $this->getReflectionClass($class);
        $class     = $reflClass->name;
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

        foreach ($values as $path => $value) {
            if (empty($this->reflectionProperties[$class][$path])) {
                continue;
            }

            /** @var Column|Relation $annotation */
            $annotation = $this->reflectionProperties[$class][$path]['annotation'];
            /** @var \ReflectionProperty $reflProperty */
            $reflProperty = $this->reflectionProperties[$class][$path]['property'];
            $castedValue = $this->castValueByAnnotation($value, $annotation);

            $reflProperty->setAccessible(true);
            $reflProperty->setValue($instance, $castedValue);

        }

        if ($cacheable) {
            $this->instancePool[$cacheKey] = $instance;
        }

        return $instance;
    }

    /**
     * Cast value by annotation
     *
     * @param mixed $value
     * @param Column|Relation|Delegated $annotation
     *
     * @return mixed
     */
    private function castValueByAnnotation($value, $annotation)
    {
        if (!is_object($annotation)) {
            throw new \InvalidArgumentException("Annotation must be an object");
        }

        switch (get_class($annotation)) {
            case Column::class:
                return $this->getValueFromColumnAnnotation($value, $annotation);
            case Relation::class:
                return $this->getValueFromRelationAnnotation($value, $annotation);
            case Delegated::class:
                return $this->getValueFromDelegatedAnnotation($value, $annotation);
        }

        throw new \InvalidArgumentException("Annotation " . get_class($annotation) . " is not supported.");
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
     * Resolve a Column annotation by recursively calling the cast value
     *
     * @param mixed  $values
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
     * Resolve a Relation annotation by recursively calling the hydrate action
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
     * Resolve a Delegated annotation by calling the hydrate action
     *
     * @param array     $values
     * @param Delegated $annotation
     *
     * @return mixed|\Justimmo\Api\ResultSet\ResultSet
     */
    protected function getValueFromDelegatedAnnotation($values, Delegated $annotation)
    {
        return $this->hydrate([
            $annotation->targetPath => $values,
        ], $annotation->targetEntity);
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

        $reflClass = new \ReflectionClass($class);
        $isValid   = false;

        foreach ($this->reader->getClassAnnotations($reflClass) as $annotation) {
            if (in_array(get_class($annotation), [Entity::class, CollectionAnnotation::class])) {
                $isValid = true;
            }
            $this->classAnnotations[$reflClass->name][get_class($annotation)] = $annotation;
        }

        if (!$isValid) {
            throw new \InvalidArgumentException($class . ' is missing annotation Justimmo\Api\Annotation\Entity or Justimmo\Api\Annotation\Entity\Collection.');
        }

        $class = $reflClass->name;
        foreach ($reflClass->getProperties() as $reflProperty) {
            $property = $reflProperty->getName();

            foreach ($this->reader->getPropertyAnnotations($reflProperty) as $annotation) {
                if (in_array(get_class($annotation), [Column::class, Relation::class, Delegated::class])) {
                    $path = $annotation->path ?: $property;

                    $this->reflectionProperties[$class][$path] = [
                        'property'   => $reflProperty,
                        'annotation' => $annotation,
                    ];
                }
            }
        }

        return $this->reflectionClasses[$class] = $reflClass;
    }
}