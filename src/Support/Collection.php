<?php

namespace LeaReift\OcrSpace\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    protected array $array;

    public function __construct(?array $array = null)
    {
        $this->array = $array ?? [];
    }

    public static function make(?array $array): self
    {
        return new Collection($array);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->array);
    }

    public function offsetExists(mixed $offset): bool {
        return array_key_exists($offset, $this->array);
    }

    public function get(int|string $offset): mixed 
    {
        return $this->array[$offset] ?? null;
    }

    public function offsetGet(mixed $offset): mixed {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        $this->array[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void {
        unset($this->array[$offset]);
     }

    public function count(): int {
        return count($this->array);
    }

    public function toArray(): array 
    {
        return $this->array;
    }

    public function keys(): Collection
    {
        return static::make(array_keys($this->array));
    }

    public function map(callable $callback): Collection 
    {
        return static::make(array_map($callback, $this->array, $this->keys()->toArray()));
    }

    public function mapIntoCollection(): Collection 
    {
        return $this->map(fn(array|Traversable $item) => Collection::make($item));
    }

}
