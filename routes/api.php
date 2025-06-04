<?php

use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\Bank\FetchBankController;
// use App\Http\Controllers\Bank\UpdateBankController;
use App\Http\Controllers\Bank\BankController;
use App\Http\Controllers\Bank\BranchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// /login is publicly accessible for authentication.
Route::post('/login', LoginController::class . '@login')->name('api.login');

// /bank_details and /update_expiry_date require a valid Sanctum token in the Authorization header (Bearer <token>).
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bank/get-bank-details', [BankController::class, 'getBankDetails'])->name('api.bank_details');
    // Route::get('/banks', [FetchBankController::class, 'listBanks'])->name('ListBanks');
    // Route::get('/banks/details', [FetchBankController::class, 'listAllDetails'])->name('BankDetailsList');
    Route::post('/bank/update_expiry_date', [BankController::class, 'updateExpiry'])->name('api.update_expiry_date');
    Route::post('/bank/get-branch-details', [BranchController::class, 'getBranchDetails'])->name('api.branch_details');

});