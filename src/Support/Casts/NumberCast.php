<?php

namespace Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Support\ValueObjects\Number;

class NumberCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Number
    {
        return new Number($value);
    }
    public function set(Model $model, string $key, mixed $value, array $attributes): Number|string|int|float
    {
        if (!$value instanceof Number)
            $value = new Number($value);

        return $value->value;
    }
}
