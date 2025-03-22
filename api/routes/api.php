<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlantController;

// auth routes
Route::get('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post("/logout", [AuthController::class, 'logout'])->middleware("jwt.auth");
Route::get("/profile", [AuthController::class, 'profile'])->middleware("jwt.auth");

Route::apiResource("plants", PlantController::class);
Route::apiResource("categories", CategoryController::class);
Route::apiResource("orders", OrderController::class)->middleware("jwt.auth");

Route::get("orders/{order}/plants", [OrderController::class, "plantsOrder"]);
Route::get("orders/{order}/cancel", [OrderController::class, "cancel"]);
