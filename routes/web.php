<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\NavController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as MiddlewareRedirectIfAuthenticated;

use function Pest\Laravel\delete;
use function Pest\Laravel\withoutMiddleware;

// Rutas públicas
Route::get('/home/profile', [ProfileController::class, 'profile'])->middleware('-auth')->name('profile');
Route::post('home/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('home/profile/deleteAccount', [ProfileController::class, 'deleteAccount'])->name('delete.account');
Route::post('home/profile/updatePassword', [ProfileController::class, 'updatePassword'])->name('update.password');

Route::get('/muestras',[NavController::class, 'muestras'])->name('muestras');

Route::get('/', [IndexController::class, 'main'])->name('welcome')->middleware('-guest');
Route::get('/home', [NavController::class, 'home'])->name('home');


Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Rutas protegidas

    // Otras rutas que requieren autenticación


