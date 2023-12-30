<?php

namespace Database\Factories;

use Domain\Product\Models\Option;
use Domain\Product\Models\OptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Product\Models\OptionValue>
 */
class OptionValueFactory extends Factory
{
    protected $model = OptionValue::class;
    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->word()),
            'option_id' => Option::query()->inRandomOrder()->value('id'),
        ];
    }
}
