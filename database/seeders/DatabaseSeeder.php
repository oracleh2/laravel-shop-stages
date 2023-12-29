<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\OptionFactory;
use Database\Factories\OptionValueFactory;
use Database\Factories\PropertyFactory;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        BrandFactory::new()->count(20)->create();
        $properties = PropertyFactory::new()->count(10)->create();
        $options = OptionFactory::new()->count(2)->create();
        $optionsValues = OptionValueFactory::new()->count(10)->create();
        CategoryFactory::new()->count(20)
            ->has(
                Product::factory(rand(5,15))
                    ->hasAttached($properties, function(){
                        return ['value' => ucfirst(fake()->word())];
                    })
                    ->hasAttached($optionsValues)
            )
            ->create();

    }
}
