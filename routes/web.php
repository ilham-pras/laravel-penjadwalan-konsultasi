<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DurasiKonsultasiController;
use App\Http\Controllers\JamOperasionalController;
use App\Http\Controllers\ZoomController;
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


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['verify' => true]);

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');


// Admin Route
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('kalender', KalenderController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('data-penjadwalan/jam', JamOperasionalController::class);
    Route::resource('data-penjadwalan/jadwal', KonsultasiController::class);
    Route::resource('data-penjadwalan/jenis', DurasiKonsultasiController::class);

    Route::get('/zoom/connect', [ZoomController::class, 'redirectToZoom'])->name('zoom.connect');
    Route::get('/zoom/callback', [ZoomController::class, 'handleZoomCallback'])->name('zoom.callback');

    Route::get('/google/login', [SocialiteController::class, 'redirectOnGoogle'])->name('google.login');
    Route::get('/google/redirect', [SocialiteController::class, 'openGoogleAccountDetails'])->name('google.callback');
});

// User Route
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::resource('kalender', KalenderController::class);
    Route::resource('profile', ProfileController::class);
});
