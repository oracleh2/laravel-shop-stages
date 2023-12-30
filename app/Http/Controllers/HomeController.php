<?php

namespace App\Http\Controllers;

use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Domain\Product\ViewModels\ProductViewModel;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = CategoryViewModel::make()->homePage();
        $brands = BrandViewModel::make()->homePage();
        $products = ProductViewModel::make()->homePage();

        return view('index', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products
        ]);
    }
}
