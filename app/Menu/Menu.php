<?php

namespace App\Menu;

use Countable;
use Illuminate\Support\Collection;
use Iterator;
use IteratorAggregate;
use Support\Traits\Makeable;
use Traversable;

class Menu implements IteratorAggregate, Countable
{
    use Makeable;
    protected array $items;
    public function __construct(MenuItem ...$items)
    {
        $this->items = $items;
    }

    public function all(): Collection
    {
        return Collection::make($this->items);
    }

    public function add(MenuItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function addIf(MenuItem $item, bool|callable $condition): self
    {
        if(is_callable($condition) ? $condition() : $condition)
            $this->items[] = $item;

        return $this;
    }

    public function remove(MenuItem $item): self
    {
        $this->items = $this->all()
            ->filter(fn(MenuItem $element) => $element !== $item)
            ->toArray();

        return $this;
    }

    public function removeByLink(string $link): self
    {
        $this->items = $this->all()
            ->filter(fn(MenuItem $element) => $element->link() !== $link)
            ->toArray();

        return $this;
    }
    public function removeByLabel(string $label): self
    {
        $this->items = $this->all()
            ->filter(fn(MenuItem $element) => $element->label() !== $label)
            ->toArray();

        return $this;
    }

    public function getIterator(): Traversable
    {
        return $this->all();
    }

    public function count(): int
    {
        return $this->all()->count();
    }
}
