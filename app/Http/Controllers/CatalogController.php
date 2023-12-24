<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
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

        $validate = Validator::make(
            $request->all(),
            [
                'q' => 'sometimes|string|max:255',
            ]
        );
        if($validate->failed()){
            flash()->warning('По вашему запросу ничего не найдено');
            return redirect()->back();
        }
        $validated = $validate->validated();

        $brands = Brand::query()
            ->select(['id', 'title'])
            ->has('products')
            ->get();

        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')
            ->get();

        $products = Product::search($validated['q'] ?? '')
            ->query( function (Builder $query) use ($category) {
                return $query
                    ->select(['id', 'title', 'slug', 'price', 'thumbnail'])
                    ->when($category->exists, function (Builder $query) use ($category) {
                        $query
                            ->whereRelation(
                                'categories',
                                'categories.id',
                                '=',
                                $category->id
                            );
                    })
                    ->filtered()
                    ->sorted();
            })
            ->paginate(12);

//        $products->each(function (Product $product) {
//            $product->setRelation('brand', Brand::query()->find($product->brand_id));
//        });

        return view('catalog.index', compact('categories', 'brands', 'products', 'category'));
    }
}
