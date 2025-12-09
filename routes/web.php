<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PropertyController::class, 'home'])->name('main');

Route::resource('properties', PropertyController::class);

Route::get('/login', [UserController::class, 'loginView'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('auth.login');

Route::get('/register', [UserController::class, 'registerView'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('auth.register');

//API Google
Route::get('/auth/redirect', [UserController::class, 'googleRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->name('auth.google.callback');

Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
Route::post('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::prefix('backoffice')->name('backoffice')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    });
    Route::get('/users', [UserController::class, 'index'])->name('.users');

    Route::get('/properties', [PropertyController::class, 'properties'])->name('.properties');
    Route::get('/transactions', function () {
        return view('admin.transaction.index');
    })->name('.transactions');
});
