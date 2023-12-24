<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\HomeController;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_page_success(): void
    {
        $this->assertGuest();

        CategoryFactory::new()
            ->count(5)
            ->create([
                'on_home_page' => true,
                'sorting' => 999,
            ]);

        $category = CategoryFactory::new()
            ->createOne([
                'on_home_page' => true,
                'sorting' => 1,
            ]);

        BrandFactory::new()
            ->count(5)
            ->create([
                'on_home_page' => true,
                'sorting' => 999,
            ]);

        $brand = BrandFactory::new()
            ->createOne([
                'on_home_page' => true,
                'sorting' => 1,
            ]);

        ProductFactory::new()
            ->count(5)
            ->create([
                'on_home_page' => true,
                'sorting' => 999,
            ]);

        $product = ProductFactory::new()
            ->createOne([
                'on_home_page' => true,
                'sorting' => 1,
            ]);

        $response = $this->get(action([HomeController::class, 'index']));

        $response
            ->assertOk()
            ->assertViewHas('categories.0', $category)
            ->assertViewHas('brands.0', $brand)
            ->assertViewHas('products.0', $product);


    }
}
