<?php

namespace App\Providers;

use App\Models\Plant;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        Route::bind('plant', function ($value) {
            return Plant::where('slug', $value)->firstOrFail();
        });
    }
}
