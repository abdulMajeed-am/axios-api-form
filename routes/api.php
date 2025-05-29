<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', AuthController::class . '@login')->name('api.login');
Route::post('/bank_details', AuthController::class . '@bank_details')
    ->name('api.bank_details')
    ->middleware('auth:sanctum');