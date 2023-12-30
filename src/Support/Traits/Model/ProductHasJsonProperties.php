<?php

namespace Support\Traits\Model;

use App\Jobs\ProductJsonProperties;
use Domain\Product\Models\Product;

trait ProductHasJsonProperties
{
    protected static function bootHasJsonProperties(): void
    {
        static::created(function (Product $product) {
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
        static::updated(function (Product $product) {
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
    }
}
