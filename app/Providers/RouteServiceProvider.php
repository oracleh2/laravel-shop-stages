<?php

namespace App\Providers;

use App\Contracts\RouteRegistrar;
use App\Routing\AppRegistrar;
use App\Routing\AuthRegistrar;
use http\Exception\RuntimeException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    protected array $registrars = [
        AppRegistrar::class,
        AuthRegistrar::class,
    ];
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

//        $this->routes(function (Registrar $router) {
//            $this->mapRoutes($router, $this->registrars);
//        });
    }

    protected function mapRoutes(Registrar $router, array $registrars): void
    {
        foreach ($registrars as $registrar) {
            if(!class_exists($registrar) || !is_subclass_of($registrar, RouteRegistrar::class)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot map routes \'$s\', it is not valid routes class',
                        $registrar
                    )
                );
            }
            (new $registrar)->map($router);
        }
    }
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(500)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response(
                        'Too many requests. Please try again later.', ResponseAlias::HTTP_TOO_MANY_REQUESTS, $headers);
                })
                ;
        });
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

    }
}
