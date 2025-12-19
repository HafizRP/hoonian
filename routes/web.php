<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PropertyController::class, 'home'])->name('main');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show')->whereNumber('property');

// Auth Guest (Hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'loginView'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('auth.login');
    Route::get('/register', [UserController::class, 'registerView'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('auth.register');

    // Google Auth
    Route::get('/auth/redirect', [UserController::class, 'googleRedirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // User Profile
    Route::prefix('profile')->name('users.profile')->group(function () {
        Route::get('/', [UserController::class, 'profile']);
        Route::post('/update', [UserController::class, 'updateProfile'])->name('.update');
    });

    // Bidding Transactions
    Route::prefix('bidding')->name('bidding.')->group(function () {
        Route::get('/', [TransactionController::class, 'listBidding'])->name('list');
        Route::post('/create', [TransactionController::class, 'createBidding'])->name('create');
        Route::patch('/{id}/accept', [TransactionController::class, 'accept'])->name('accept');
        Route::patch('/{id}/decline', [TransactionController::class, 'decline'])->name('decline');
    });

    // Resource properties untuk auth user (create, edit, update, delete)
    Route::resource('properties', PropertyController::class)->except(['index', 'show']);
});

/*
|--------------------------------------------------------------------------
| Backoffice / Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth']) // Removed admin middleware
    ->prefix('backoffice')
    ->name('backoffice.')
    ->group(function () {
        Route::get('/', [UserController::class, 'adminDashboard'])->name('index');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/properties', [PropertyController::class, 'properties'])->name('properties');
        Route::get('/properties/create', [PropertyController::class, 'backofficeCreate'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/edit/{id}', [PropertyController::class, 'backofficeEdit'])->name('properties.edit');
        Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
        Route::get('/transactions', [TransactionController::class, 'backofficeList'])->name('transactions');
});
