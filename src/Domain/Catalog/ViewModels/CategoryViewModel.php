<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class CategoryViewModel
{
    use Makeable;
    public function homePage(): Collection|array
    {
        return Cache::remember('home_page_categories', 60, function () {
            return Category::query()
                ->homePage()
                ->get();
        });
    }
    public function catalog(): Collection|array
    {
        return Cache::remember('catalog_categories', 60, function () {
            return Category::query()
                ->select(['id', 'title', 'slug'])
                ->has('products')
                ->get();
        });
    }
}
