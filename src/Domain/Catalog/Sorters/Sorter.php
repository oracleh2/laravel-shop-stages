<?php

namespace Domain\Catalog\Sorters;

use Domain\Product\Collections\ProductCollection;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Stringable;

class Sorter
{
    public const SORT_KEY = 'sort';
    public function __construct(
        protected array $columns = [],
    )
    {
    }

    public function run(ProductQueryBuilder $builder): ProductQueryBuilder
    {
        $sortData = $this->sortData();
        return $builder
            ->when($sortData->contains($this->columns()), function (Builder $builder) use ($sortData) {
                $builder
                    ->orderBy(
                        (string) $sortData->remove('-'),
                        $sortData->contains('-') ? 'desc' : 'asc'
                    );

            });
    }

    public function key(): string
    {
        return self::SORT_KEY;
    }

    public function columns(): array
    {
        return $this->columns;
    }
    public function sortData(): Stringable
    {
        return request()->str($this->key());
    }

    public function is_active(string $column, string $direction = 'asc'): bool
    {
        $column = trim($column, '-');
        if(strtolower($direction) === 'desc')
            $column = '-' . $column;

        return request($this->key()) === $column;

    }

}
