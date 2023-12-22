<?php

namespace App\Models;

use App\Traits\Model\HasSlug;
use App\Traits\Model\HasThumbnail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function scopeHomePage(Builder $builder)
    {
        $builder->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(8);
    }

    protected function thumbnailDir(): string
    {
        return 'products';
    }
}
