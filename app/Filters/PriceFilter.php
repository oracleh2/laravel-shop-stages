<?php

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class PriceFilter extends AbstractFilter
{

    public function title(): string
    {
        return 'Цена';
    }

    public function key(): string
    {
        return 'price';
    }

    public function apply(Builder $builder): Builder
    {
        return $builder
            ->when($this->requestValue(), function (Builder $query) {
                $query->whereBetween('price', [
                    $this->requestValue('from', 0),
                    $this->requestValue('to', 9999999),
                ]);
            });
    }

    public function values(): array
    {
        return [
            'from' => 0,
            'to' => 9999999
        ];
    }

    public function view(): string
    {
        return 'catalog.filters.price';
    }
}
