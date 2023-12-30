<?php

namespace App\Http\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_success_response():void
    {
        $properties = PropertyFactory::new()->count(10)->create();
        $product = ProductFactory::new()
            ->hasAttached($properties, function () {
                return ['value' => ucfirst(fake()->word())];
            })
            ->create();

        $this->get(action(ProductController::class, $product))
            ->assertOk();
    }

}
