<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        $this->app->bind(TelegramBotApiContract::class, TelegramBotApi::class);

        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
//        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
//            logger()
//                ->channel('telegram')
//                ->error('Long query detected: ' . $connection->query()->toRawSql());
//        });

        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                logger()
                    ->channel('telegram')
                    ->error('Long request detected: ' . request()->fullUrl());
            }
        );

        Password::defaults(function(){
            return Password::min(8)
                ->letters()
                ->numbers()
                ->symbols();
        });
    }
}
