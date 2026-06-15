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

/*
* Public Routes
* Routes available to all visitors without authentication.
* These routes provide access to the landing page, map view,
* feedback viewing, and route flagging functionality.
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/map', [MapController::class, 'index'])->name('map');

// Route feedback functionality
Route::get('/routes/{route}/feedback', [FeedbackController::class, 'index']);
Route::post('/routes/{route}/feedback', [FeedbackController::class, 'store']);

// Route moderation functionality
Route::post('/routes/{route}/flag', [MapController::class, 'toggleFlag'])
    ->name('routes.flag');

/*
* Guest Routes
* Routes accessible only to unauthenticated users.
* Used for registration and login functionality.
*/

Route::middleware(['guest'])->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('show.register');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('show.login');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});

/*
* Logout Route
* Ends the current user session.
*
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
* Authenticated User Routes
* Routes available only to logged-in users.
* Includes profile management, route management,
* favorites, and administration functionality.
*/

Route::middleware(['auth'])->group(function () {

    // User dashboard
    Route::get('/home', function () {
        return view('home');
    })->name('home')->middleware('auth');

    // Route creation
    Route::get('/createroute', [MapController::class, 'create'])
        ->name('create.route');

    Route::post('/createroute', [MapController::class, 'store'])
        ->name('routes.store');

    // User settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // User profile management
    Route::get('/profile', [UserProfileController::class, 'show'])
        ->name('profile');

    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    Route::post('/profile/bio', [UserProfileController::class, 'updateBio'])
        ->name('profile.bio');

    // Route editing and deletion
    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])
        ->name('routes.destroy');

    Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])
        ->name('routes.edit');

    Route::put('/routes/{route}', [RouteController::class, 'update'])
        ->name('routes.update');

    // Favorite routes functionality
    Route::post('/routes/{route}/favorite', [FavoriteController::class, 'toggle'])
        ->middleware('auth')
        ->name('routes.favorite');

    // Administration panel
    Route::get('/admin/users', [AdminController::class, 'index'])
        ->name('admin.users');

    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])
        ->name('admin.users.delete');
});