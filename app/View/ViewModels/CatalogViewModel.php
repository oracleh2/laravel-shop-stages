<?php

namespace App\View\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\ViewModels\ProductViewModel;
use Illuminate\Support\Facades\Validator;
use Spatie\ViewModels\ViewModel;

class CatalogViewModel extends ViewModel
{
    public function __construct(
        public Category $category
    )
    {
        //
    }

    public function products()
    {
        $validate = Validator::make(request()->all(), ['q' => 'sometimes|string|max:255',]);
        if($validate->failed()){
            flash()->warning('По вашему запросу ничего не найдено');
            return redirect()->back();
        }
        $validated = $validate->validated();

        return ProductViewModel::make()
            ->catalog(
                category: $this->category,
                page: request('page', 1),
                q: $validated['q'] ?? ''
            );
    }
    public function categories()
    {
        return CategoryViewModel::make()->catalog();
    }
}
