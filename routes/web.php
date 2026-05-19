<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;

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

    Route::get('/createroute', [MapController::class, 'create'])->name('create.route');

    Route::post('/createroute', [MapController::class, 'store'])->name('routes.store');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');
    Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])->name('routes.edit');
    Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');

    Route::post('/routes/{route}/favorite', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('routes.favorite');

        Route::get('/admin/users', [AdminController::class, 'index'])
        ->name('admin.users');

    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])
        ->name('admin.users.delete');
});

