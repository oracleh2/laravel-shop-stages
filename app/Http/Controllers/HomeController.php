<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = CategoryViewModel::make()->homePage();
        $brands = BrandViewModel::make()->homePage();
        $products = Product::query()
            ->homePage()
            ->get();
//        $products->each(function (Product $product) {
//            $product->setRelation('brand', Brand::query()->find($product->brand_id));
//        });
        return view('index', compact('categories', 'brands', 'products'));
    }
}
