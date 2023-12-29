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

class ProductController extends Controller
{
    public function __invoke(Product $product)
    {
        $product->load(['optionValues.option']);
        $options = $product->optionValues->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });

        if(session()->has('also')) {
            $viewed = array_keys(session('also'));
            $viewed = Product::query()
                ->whereIn('id', $viewed)
                ->where('id', '!=', $product->id)
                ->take(8)
                ->get();
        }

        session()
            ->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'viewed' => $viewed ?? null
        ]);
    }
}
