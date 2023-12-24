<?php

namespace Domain\Auth\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
//use Faker\Factory;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];
    public function register(): void {}
    public function boot(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
    }
}
