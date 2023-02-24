<?php

declare(strict_types=1);

/** @template T */
class Collection implements Countable, Iterator, ArrayAccess
{
    protected int $position = 0;
    protected string $itemType;
    /**
     * @var T[]
     */
    protected array $items;

    /**
     * @param T[] $items 
     */
    public function __construct(array $items)
    {
        if (count($items) > 0) {
            $this->itemType = $this->resolveItemType($items[0]);
        }
        $this->items = array_values($items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset 
     * @return T 
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset 
     * @param T $value 
     * @return void 
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateType($value);
        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /** @return T */
    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    /** @return int */
    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @param T $item 
     * @return void 
     */
    public function add(mixed $item): void
    {
        $this->validateType($item);
        $this->items[] = $item;
    }

    /**
     * @param Collection<T>|array<T> $items 
     * @return void 
     */
    public function addAll(self|array $items): void
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @param T $item 
     * @return void 
     */
    public function remove(mixed $item): void
    {
        foreach ($this->items as $key => $_item) {
            if ($item === $_item) {
                $this->offsetUnset($key);
                $this->items = array_values($this->items);
                return;
            }
        }
    }

    /**
     * @param Collection<T>|array<T> $items 
     * @return void 
     */
    public function removeAll(self|array $items): void
    {
        foreach ($items as $item) {
            $this->remove($item);
        }
    }

    /**
     * Remove all items that match given condition
     * @param callable $condition
     */
    public function removeWhere(callable $condition): void
    {
        $this->removeAll($this->findWhere($condition));
    }

    /**
     * Clears/Removes all items in this collection
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * Check whether this collection contains given object
     * @param object $item 
     * @return bool 
     */
    public function contains(object $item): bool
    {
        foreach ($this->items as $_item) {
            if ($_item === $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * Find all items that match given condition
     * @param callable $condition
     * @return static New collection containing matched items
     */
    public function findWhere(callable $condition): static
    {
        $found = [];
        foreach ($this->items as $item) {
            if ($condition($item)) {
                $found[] = $item;
            }
        }
        return new static($found);
    }

    private function resolveItemType(mixed $item): string
    {
        if (is_object($item)) {
            $parentClass = get_parent_class($item);
            return $parentClass ? $parentClass : get_class($item);
        }
        return gettype($item);
    }

    /**
     * Validates that given item is of same type as other items in this collection
     * @param mixed $item 
     * @return void When item types match
     * @throws TypeError When given item is not of same type
     */
    private function validateType(mixed $item): void
    {
        if ($this->isEmpty()) {
            return;
        }

        $actualType = $this->resolveItemType($item);
        if ($this->itemType !== $actualType) {
            throw new TypeError("Expected type {$this->itemType}, got {$actualType}");
        }
    }
}
