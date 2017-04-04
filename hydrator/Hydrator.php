<?php

namespace app\hydrator;

class Hydrator
{
    private $reflectionClassMap;

    public function extract($object, array $only = [])
    {
        $result = [];
        $reflection = new \ReflectionObject($object);

        $fields = $only ?: array_map(function (\ReflectionProperty $property) {
            return $property->getName();
        }, $reflection->getProperties());

        foreach ($fields as $field) {
            if (!$property = $reflection->getProperty($field)) {
                throw new \InvalidArgumentException('Undefined property ' . $name);
            }
            $property->setAccessible(true);
            $result[$field] = $property->getValue($object);
        }
        return $result;
    }

    public function hydrate($target, array $data)
    {
        if (is_object($target)) {
            $reflection = $this->getReflectionClass(get_class($target));
        } else {
            $reflection = $this->getReflectionClass($target);
            $target = $reflection->newInstanceWithoutConstructor();
        }
        foreach ($data as $name => $value) {
            $property = $reflection->getProperty($name);
            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }
            $property->setValue($target, $value);
        }
        return $target;
    }

    /**
     * @param string $className
     * @return \ReflectionClass
     */
    protected function getReflectionClass($className)
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new \ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}