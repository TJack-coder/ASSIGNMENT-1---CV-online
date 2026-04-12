<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file contains ALL routes for the Online CV Management System
| Based on the assignment PDF + your group task division
|
*/

// ====================== PUBLIC ROUTES (No login required) ======================
Route::get('/', function () {
    return view('welcome');        // You can change this later to a nice landing page
})->name('home');

// ====================== AUTHENTICATION ROUTES (Person B) ======================
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');

    Route::post('/logout', 'logout')->name('logout');
});

// ====================== JOB SEEKER ROUTES (Person B) ======================
Route::middleware(['auth'])->group(function () {

    Route::controller(CvController::class)->group(function () {
        Route::get('/cv/create', 'create')->name('cv.create');
        Route::post('/cv', 'store')->name('cv.store');
    });

});


// ====================== FALLBACK (Optional) ======================
Route::fallback(function () {
    return redirect()->route('login');
});