<?php

namespace Database\Factories;

use Domain\Product\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;
    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->word()),
        ];
    }
}
