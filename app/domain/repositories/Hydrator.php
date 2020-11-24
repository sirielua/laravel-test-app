<?php

namespace App\domain\repositories;

class Hydrator
{
    public function hydrate($class, array $data)
    {
        $reflection = new \ReflectionClass($class);
        $target = $reflection->newInstanceWithoutConstructor();
        foreach ($data as $name => $value) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($target, $value);
        }
        return $target;
    }
}
