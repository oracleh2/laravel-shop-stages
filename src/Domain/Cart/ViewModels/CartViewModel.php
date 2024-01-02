<?php

namespace Domain\Cart\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Support\Traits\Makeable;

class CartViewModel
{
    use Makeable;

}
