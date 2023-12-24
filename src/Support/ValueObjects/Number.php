<?php

namespace Support\ValueObjects;

use Stringable;
use InvalidArgumentException;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Support\Casts\NumberCast;
use Support\Traits\Makeable;

class Number implements Stringable, Castable
{
    use Makeable;
    private readonly int $scale;
//    public string $value;
    private array $currencies = [
        'RUB' => '₽',
    ];

    public function __construct(
        public readonly Number|string|int|float $value = 0,
        private readonly string $currency = 'RUB',

    )
    {
        if ($value instanceof static)
            $value = $value->value;

        if (!is_numeric($value))
            throw new InvalidArgumentException(
                "Значение [{$value}] должно быть числом",
            );

        if($this->isNegative())
            throw new InvalidArgumentException(
                "Значение [{$value}] должно быть положительным числом",
            );
        if(!in_array($this->currency, array_keys($this->currencies)))
            throw new InvalidArgumentException(
                "Валюта [{$this->currency}] не поддерживается",
            );
        $this->scale = config('price.prices.scale');
    }
    public function isNegative(): bool
    {
        return $this->value < 0;
    }
    public function isPositive(): bool
    {
        return $this->value > 0;
    }
    public function isZero(): bool
    {
        return $this->value === 0;
    }
    public function add(Number|string|int|float $number = 0): static
    {
        $number = new static($number);
        $value = bcadd($this->value, $number->value, $this->scale);
        return new static($value);
    }

    public function sub(Number|string|int|float $number = 0): static
    {
        $number = new static($number);
        $value = bcsub($this->value, $number->value, $this->scale);
        return new static($value);
    }

    public function mul(Number|string|int|float $number = 0): static
    {
        $number = new static($number);
        $value = bcmul($this->value, $number->value, $this->scale);
        return new static($value);
    }

    public function div(Number|string|int|float $number = 0): static
    {
        $number = new static($number);
        $value = bcdiv($this->value, $number->value, $this->scale);
        return new static($value);
    }

    public function eq(Number|string|int|float $number = 0): bool
    {
        $number = new static($number);
        $result = bccomp($this->value, $number->value, $this->scale);
        return ($result === 0);
    }

    public function gt(Number|string|int|float $number = 0): bool
    {
        $number = new static($number);
        $result = bccomp($this->value, $number->value, $this->scale);
        return ($result === 1);
    }

    public function gte(Number|string|int|float $number = 0): bool
    {
        return $this->eq($number) || $this->gt($number);
    }

    public function lt(Number|string|int|float $number = 0): bool
    {
        $number = new static($number);
        $result = bccomp($this->value, $number->value, $this->scale);
        return ($result === -1);
    }

    public function lte(Number|string|int|float $number = 0): bool
    {
        return $this->eq($number) || $this->lt($number);
    }
    public function value(): string
    {
        return number_format($this->value, $this->scale, '.', ' ');
    }
    public function raw(): string
    {
        return $this->value;
    }
    public function currency(): string
    {
        return $this->currency;
    }
    public function currencySymbol(): string
    {
        return $this->currencies[$this->currency];
    }
    public function __toString(): string
    {
        return $this->value;
    }
    public function price(): string
    {
        return number_format($this->value, $this->scale, '.', ' ') . ' ' . $this->currencySymbol();
    }
    public static function castUsing(array $arguments): string
    {
        return NumberCast::class;
    }
}
