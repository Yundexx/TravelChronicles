<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/map', [MapController::class, 'index'])->name('map');

Route::get('/routes/{route}/feedback', [FeedbackController::class, 'index']);
Route::post('/routes/{route}/feedback', [FeedbackController::class, 'store']);
Route::post('/routes/{route}/flag', [MapController::class, 'toggleFlag'])->name('routes.flag');

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home')->middleware('auth');

    Route::get('/createroute', function () {
        return view('createroute');
    })->name('create.route');

    Route::post('/createroute', [MapController::class, 'store'])->name('routes.store');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
});