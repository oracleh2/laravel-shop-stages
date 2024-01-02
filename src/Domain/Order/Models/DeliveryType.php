<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Support\ValueObjects\Number;

class DeliveryType extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'price',
        'with_address',
    ];

    protected $casts = [
        'price' => Number::class,
    ];
}
