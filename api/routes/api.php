<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\StatsController;

// auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post("/logout", [AuthController::class, 'logout'])->middleware("jwt.auth");
Route::get("/profile", [AuthController::class, 'profile'])->middleware("jwt.auth");

Route::apiResource("plants", PlantController::class);
Route::apiResource("categories", CategoryController::class);
Route::apiResource("orders", OrderController::class)->middleware("jwt.auth");

Route::get("orders/{order}/plants", [OrderController::class, "plantsOrder"]);
Route::get("orders/{order}/cancel", [OrderController::class, "cancel"]);

Route::prefix("admin")->group(function () {
    Route::get("/sales", [StatsController::class, "sales"]);
    Route::get("/popular-plants", [StatsController::class, "popularPlants"]);
    Route::get("/plants-repartition", [StatsController::class, "plantsRepartition"]);
})->middleware("jwt.auth");
