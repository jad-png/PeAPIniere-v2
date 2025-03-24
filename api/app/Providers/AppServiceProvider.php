<?php

namespace App\Providers;

use App\DAOs\CategoryDAO;
use App\DAOs\Interfaces\CategoryInterface;
use App\Models\Category;
use App\Models\Plant;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryDAO::class);
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
