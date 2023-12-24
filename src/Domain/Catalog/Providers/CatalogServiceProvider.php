<?php

namespace Domain\Catalog\Providers;

use Domain\Auth\Providers\ActionsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }
    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
    }
}
