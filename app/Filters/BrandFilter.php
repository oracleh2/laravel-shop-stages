<?php

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Builder;

class BrandFilter extends AbstractFilter
{

    public function title(): string
    {
        return 'Бренды';
    }

    public function key(): string
    {
        return 'brands';
    }

    public function apply(Builder $builder): Builder
    {
        return $builder
            ->when($this->requestValue(), function (Builder $query) {
                $query->whereIn('brand_id', request('filters.brands'));
            });
    }

    public function values(): array
    {
        return Brand::query()
            ->select(['id', 'title'])
            ->has('products')
            ->get()
            ->pluck('title', 'id')
            ->toArray();
    }

    public function view(): string
    {
        return 'catalog.filters.brands';
    }
}
