<?php

namespace LeaReift\OcrSpace\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 * @implements \Illuminate\Support\Enumerable<TKey, TValue>
 */
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

    public function isEmpty(): bool
    {
        return empty($this->array);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->array);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->array);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->array);
    }

    public function get(int|string|null $offset): mixed
    {
        return $this->array[$offset] ?? null;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->array[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->array[$offset]);
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * @return Collection<TKey>
     */
    public function keys(): Collection
    {
        return static::make(array_keys($this->array));
    }

    public function map(callable $callback): Collection
    {
        return static::make(array_map($callback, $this->array, $this->keys()->toArray()));
    }

    /**
     * @return ?TValue
     */
    public function first(): mixed
    {
        return $this->get(array_key_first($this->array));
    }

    public function mapIntoCollection(): Collection
    {
        return $this->map(fn (array $item) => Collection::make($item));
    }
}
