<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pipeline\Pipeline;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Support\Traits\Model\HasSlug;
use Support\Traits\Model\HasThumbnail;
use Support\ValueObjects\Number;

/**
 * @property int $brand_id
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;


    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'description',
        'price',
        'on_home_page',
        'sorting',
        'brand_id',
    ];
    protected $casts = [
        'price' => Number::class
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }


    public function scopeHomePage(Builder $builder): Builder
    {
        return $builder->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(8);
    }
    public function scopeFiltered(Builder $builder)
    {
        return app(Pipeline::class)
            ->send($builder)
            ->through(filters())
            ->thenReturn();
    }
    public function scopeSorted(Builder $builder): Builder
    {
        return $builder
            ->when(request('sort'), function(Builder $builder) {
                $column = request()->str('sort');
                if($column === null)
                    return $builder->latest();
                if($column->contains(['price', 'title'])){
                    $direction = $column->contains('-') ? 'desc' : 'asc';
                    return $builder->orderBy((string) $column->remove('-'), $direction);
                }
                else
                    return $builder;
            });
    }

    #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
    protected function thumbnailDir(): string
    {
        return 'products';
    }
}
