<?php

namespace Domain\Product\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Support\Traits\Makeable;

class ProductViewModel
{
    use Makeable;
    public function homePage(): Collection|array
    {
        return Product::query()
            ->homePage()
            ->get();
    }
    public function catalog(?Category $category, int $page): LengthAwarePaginator
    {
        return Product::query()
            ->catalogWithSearch($category)
            ->paginate(perPage: 12, page: $page);
    }
    public function viewed(Product $product): Collection|array
    {
        return Product::query()
            ->select(['id', 'title', 'slug', 'thumbnail', 'price'])
            ->where(function ($query) use ($product) {
                return $query
                    ->whereIn('id', session('also'))
                    ->where('id', '!=', $product->id);
            })
            ->take(8)
            ->get();
    }
}
