<?php

namespace App\Providers;

use App\DAOs\AuthDAO;
use App\DAOs\CategoryDAO;
use App\DAOs\Interfaces\AuthInterface;
use App\DAOs\Interfaces\CategoryInterface;
use App\DAOs\PlantDAO;
use App\DAOs\Interfaces\PlantInterface;
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
        $this->app->bind(CategoryInterface::class, CategoryDAO::class);
        $this->app->bind(PlantInterface::class, PlantDAO::class);
        $this->app->bind(AuthInterface::class, AuthDAO::class);
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
