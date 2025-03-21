<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// auth routes
Route::get('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post("/logout", [AuthController::class, 'logout'])->middleware("jwt.auth");
Route::get("/profile", [AuthController::class, 'profile'])->middleware("jwt.auth");
