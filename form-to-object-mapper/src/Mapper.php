<?php

class Mapper
{
    private array $fields = [];

    public function __construct(
        private MapperConfiguration $config
    ) {
    }

    public function mapFrom(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function mapFromGlobals(array $only = []): self
    {
        // Filter globals to include only specified keys
        if (count($only) > 0) {
            $this->fields = array_intersect_key($_POST, array_flip($only));
        } else {
            $this->fields = $_POST;
        }
        return $this;
    }

    /**
     * @template T
     * @param class-string<T>|T $classOrObject 
     * @return T
     */
    public function mapTo(string|object $classOrObject)
    {
        $reflection = new ReflectionClass($classOrObject);
        $props = $reflection->getProperties();
        $instance = $classOrObject;
        if (!is_object($classOrObject)) {
            $instance = $reflection->newInstanceWithoutConstructor();
        }
        foreach ($props as $prop) {
            if (!array_key_exists($prop->getName(), $this->fields)) {
                throw new Exception("Fields does not contain class property {$prop->getName()}");
            }
            $fieldValue = $this->fields[$prop->getName()];
            $fieldValue = $this->castType($prop->getType(), $fieldValue);
            $prop->setAccessible(true);
            $prop->setValue($instance, $fieldValue);
        }
        return $instance;
    }

    private function castType(null|ReflectionType|string $type, mixed $value): mixed
    {
        if ($type) {
            // ReflectionType::getName not documented yet
            $typeName = is_string($type) ? $type : $type->getName();
            return match ($typeName) {
                "string" => $this->config->basicSanitizationEnabled()
                    ? filter_var($value, FILTER_SANITIZE_STRING)
                    : strval($value),
                "bool", "boolean" => filter_var($value, FILTER_VALIDATE_BOOL),
                "int", "integer" => intval($value),
                "float", "double" => floatval($value),
                "array" => is_array($value) ? $this->handleArray($value) : [],
                "NULL", "null" => null,
                default => throw new Exception("Type {$type} not supported")
            };
        }
        return $value;
    }

    private function handleArray(array $arr): array
    {
        return array_map(
            fn ($item) => $this->castType(gettype($item), $item),
            $arr
        );
    }
}
