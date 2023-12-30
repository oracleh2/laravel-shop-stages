<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Domain\Product\ViewModels\ProductViewModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CatalogController extends Controller
{
    public function __invoke(?Category $category, Request $request): View|Factory|Application|RedirectResponse
    {
        $categories = CategoryViewModel::make()->catalog();
        $products = ProductViewModel::make()
            ->catalog(
                category: $category,
                page: $request->get('page', 1),
            );

        return view('catalog.index', [
            'categories' => $categories,
            'products' => $products,
            'category' => $category
        ]);
    }
}
