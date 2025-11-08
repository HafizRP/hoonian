<?php

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

Route::get('/', function () { return view('index'); })->name('main');
Route::get('/properties/{id}', function() { return view('property.detail'); })->name('property.detail');
Route::get('/properties', function() { return view('property.list'); })->name('property.list');
Route::get('/login', function() { return view('login'); })->name('login');
Route::get('/register', function() { return view('login'); })->name('register');
