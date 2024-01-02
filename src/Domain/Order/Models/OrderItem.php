<?php

namespace Domain\Order\Models;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\ValueObjects\Number;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => Number::class
    ];

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn () =>  (new Number($this->price))->mul($this->quantity), // Number::make($this->price)->mul($this->quantity),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
