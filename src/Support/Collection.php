<?php

namespace TailwindMerge\Support;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @internal
 */
class Collection
{
    /**
     * @var array<TKey, TValue>
     */
    protected array $items = [];

    /**
     * @param  self<array-key, TValue>|array<array-key, TValue>  $items
     * @return void
     */
    public function __construct(self|array $items = [])
    {
        if ($items instanceof self) {
            $items = $items->all();
        }

        $this->items = $items;
    }

    /**
     * @template TMakeKey of array-key
     * @template TMakeValue
     *
     * @param  array<TMakeKey, TMakeValue>  $items
     * @return self<TMakeKey, TMakeValue>
     */
    public static function make(array $items = []): self
    {
        return new self($items);
    }

    public function contains(string $key): bool
    {
        return in_array($key, $this->items);
    }

    /**
     * @return self<int, mixed>
     */
    public function flatten(int $depth = PHP_INT_MAX): self
    {
        return new self(Arr::flatten($this->items, $depth));
    }

    public function join(string $glue): string
    {
        return implode($glue, $this->items);
    }

    /**
     * @template TMapWithKeysKey of array-key
     * @template TMapWithKeysValue
     *
     * @param  callable(TValue, TKey): array<TMapWithKeysKey, TMapWithKeysValue>  $callback
     * @return self<TMapWithKeysKey, TMapWithKeysValue>
     */
    public function mapWithKeys(callable $callback): self
    {
        return new self(Arr::mapWithKeys($this->items, $callback)); // @phpstan-ignore-line
    }

    /**
     * @return self<int, TValue>
     */
    public function sort(): self
    {
        $items = $this->items;

        asort($items);

        return new self($items);
    }

    /**
     * @return self<int, TValue>
     */
    public function values(): self
    {
        return new self(array_values($this->items));
    }

    /**
     * @return array<TKey, mixed>
     */
    public function toArray(): array
    {
        return $this->map(fn (mixed $value): mixed => $value instanceof Collection ? $value->toArray() : $value)->all();
    }

    /**
     * @template TMapValue
     *
     * @param  callable(TValue, TKey): TMapValue  $callback
     * @return self<TKey, TMapValue>
     */
    public function map(callable $callback): self
    {
        return new self(Arr::map($this->items, $callback)); // @phpstan-ignore-line
    }

    /**
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @param  TValue  $item
     * @return $this
     */
    public function add(mixed $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param  (callable(TValue, TKey): bool)|null  $callback
     * @return ?TValue
     */
    public function first(?callable $callback = null): mixed
    {
        return Arr::first($this->items, $callback); // @phpstan-ignore-line
    }

    /**
     * @param  array<array-key, TValue>|self<array-key, TValue>  $source
     * @return self<array-key, TValue>
     */
    public function concat(array|self $source): self
    {
        $result = new self($this);

        if ($source instanceof self) {
            $source = $source->all();
        }

        foreach ($source as $item) {
            $result->push($item);
        }

        return $result;
    }

    /**
     * @param  TValue  ...$values
     * @return $this
     */
    public function push(...$values): self
    {
        foreach ($values as $value) {
            $this->items[] = $value;
        }

        return $this;
    }

    /**
     * @return self<TKey, TValue>
     */
    public function reverse(): self
    {
        return new self(array_reverse($this->items, true));
    }

    /**
     * @return self<TKey, TValue>
     */
    public function filter(): self
    {
        return new self(array_filter($this->items));
    }

    public function dd(): void
    {
        dd($this->items);
    }
}
