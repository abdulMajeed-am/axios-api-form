<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

// Route::get('/login', function () {
//     return view('auth/login');
// });/

Route::get('/dashboard', function () {
    return view('dashboard'); // Dashboard page
});

//to check database is connected
Route::get('/check-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Connected to the database successfully!";
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});
