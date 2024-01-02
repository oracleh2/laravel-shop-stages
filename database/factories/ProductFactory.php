<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
//
            'thumbnail' => $this->faker->fixturesImage('products', 'products', false),
            'description' => $this->faker->realText(),
            'price' => $this->faker->numberBetween(100, 10000),
            'quantity' => $this->faker->numberBetween(0, 10),
            'on_home_page' => $this->faker->boolean(10),
            'sorting' => $this->faker->numberBetween(1, 999),
        ];
    }

}
