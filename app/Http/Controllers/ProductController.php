<?php

namespace App\Http\Controllers;

use Domain\Product\Models\Product;
use Domain\Product\Collections\ProductCollection;
use Domain\Product\ViewModels\ProductViewModel;

class ProductController extends Controller
{
    public function __invoke(Product $product)
    {
        $product->load(['optionValues.option']);
        session()->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $product->optionValues->keyValues(),
            'viewed' => ProductViewModel::make()->viewed($product),
        ]);
    }
}
