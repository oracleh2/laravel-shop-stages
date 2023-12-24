<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class BrandViewModel
{
    use Makeable;
    public function homePage(): Collection|array
    {
        return Cache::remember('home_page_brands', 60, function () {
            return Brand::query()
                ->homePage()
                ->get();
        });
    }
}
