<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bank\FetchBankController;
use App\Http\Controllers\Bank\UpdateBankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// /login is publicly accessible for authentication.
Route::post('/login', LoginController::class . '@login')->name('api.login');
// /bank_details and /update_expiry_date require a valid Sanctum token in the Authorization header (Bearer <token>).
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bank_details', [FetchBankController::class, 'getBankDetails'])->name('api.bank_details');
    Route::post('/update_expiry_date', [UpdateBankController::class, 'updateExpiry'])->name('api.update_expiry_date');
});