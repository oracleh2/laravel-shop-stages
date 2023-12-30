<?php

namespace Domain\Catalog\Facades;





use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Domain\Catalog\Sorters\Sorter
 * @method ProductQueryBuilder|Builder run(ProductQueryBuilder|Builder $builder)
 */
class Sorter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Domain\Catalog\Sorters\Sorter::class;
    }
}
