<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\JamOperasionalController;
use Illuminate\Foundation\Auth\EmailVerificationNotification;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

// Admin Route
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('kalender', KalenderController::class);
    Route::resource('jam', JamOperasionalController::class);
    Route::resource('konsultasi', KonsultasiController::class);

    Route::get('/google/login', [SocialiteController::class, 'redirectOnGoogle'])->name('google.login');
    Route::get('/google/redirect', [SocialiteController::class, 'openGoogleAccountDetails'])->name('google.callback');
});

// User Route
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::resource('kalender', KalenderController::class);
});
