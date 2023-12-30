<?php

namespace Domain\Product\QueryBuilders;

use Domain\Catalog\Facades\Sorter;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Validator;

class ProductQueryBuilder extends Builder
{
    public function homePage(): ProductQueryBuilder
    {
        return $this->where('on_home_page', true)
                ->orderBy('sorting')
                ->limit(8);
    }
    public function filtered(): ProductQueryBuilder
    {
        return app(Pipeline::class)
            ->send($this)
            ->through(filters())
            ->thenReturn();
    }
    public function sorted(): ProductQueryBuilder
    {
        return sorter()->run($this);
    }
    public function withCategory(?Category $category): ProductQueryBuilder
    {
        return $this
            ->when($category->exists, function (ProductQueryBuilder $query) use ($category) {
                $query
                    ->whereRelation(
                        'categories',
                        'categories.id',
                        '=',
                        $category->id
                    );
            });
    }
    public function catalogWithSearch(?Category $category): \Laravel\Scout\Builder|RedirectResponse
    {
        if(request()->has('q')) {
            $validate = Validator::make(request()->all(), ['q' => 'sometimes|string|max:255',]);
            if($validate->failed()){
                flash()->warning('По вашему запросу ничего не найдено');
                return redirect()->back();
            }
            $validated = $validate->validated();
        }


        return Product::search(request('q', null))
                ->query( function (ProductQueryBuilder $query) use ($category) {
                    return $query
                        ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'json_properties'])
                        ->withCategory($category)
                        ->sorted()
                        ->filtered();
                });
    }
}
