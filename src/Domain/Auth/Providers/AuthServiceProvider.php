<?php

namespace Domain\Auth\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        //
    ];
    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
//        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');
    }


//    protected function registerEloquentFactoriesFrom($path) {
//        $this->app->make(Factory::class)->load($path);
//    }
}
