<?php

namespace App\Providers;

use App\Services\Qianwen;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Qianwen::class, function () {
            return new Qianwen(config('services.qianwen.api_key'), config('services.qianwen.secret_key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
