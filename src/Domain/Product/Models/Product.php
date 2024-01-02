<?php

namespace Domain\Product\Models;

use App\Jobs\ProductJsonProperties;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\Collections\ProductCollection;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use Support\Traits\Model\HasSlug;
use Support\Traits\Model\HasThumbnail;
use Support\Traits\Model\ProductHasJsonProperties;
use Support\Traits\Model\ProductHasRelations;
use Support\ValueObjects\Number;

/**
 * @method static ProductQueryBuilder|Product query()
 * @property int $id
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;
    use ProductHasJsonProperties;
//    use ProductHasRelations;


    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'description',
        'price',
        'quantity',
        'on_home_page',
        'sorting',
        'brand_id',
        'json_properties',
    ];
    protected $casts = [
        'price' => Number::class,
        'json_properties' => 'array',
    ];
    protected static function boot(): void
    {
        parent::boot();
        static::created(function (Product $product) {
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
        static::updated(function (Product $product) {
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }
    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
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
    public function newCollection(array $models = []): ProductCollection
    {
        return new ProductCollection($models);
    }
    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }




}
