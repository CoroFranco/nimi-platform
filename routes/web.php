<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\NavController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CourseApiController;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as MiddlewareRedirectIfAuthenticated;

use function Pest\Laravel\delete;
use function Pest\Laravel\withoutMiddleware;

// Rutas públicas
Route::get('/', [IndexController::class, 'main'])->name('welcome')->middleware('-guest');

Route::get('/home', [NavController::class, 'home'])->name('home');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/explorer/search', [NavController::class, 'search'])->name('api.courses.search');


Route::middleware(['-auth'])->group(function () {

    
    
    Route::get('/myCourses', [NavController::class, 'myCourses'])->name('myCourses');
    Route::get('/explorer', [NavController::class, 'explorer'])->name('explorer');
    Route::get('/home/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('home/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('home/profile/deleteAccount', [ProfileController::class, 'deleteAccount'])->name('delete.account');
    Route::post('home/profile/updatePassword', [ProfileController::class, 'updatePassword'])->name('update.password');

    Route::get('/create', [NavController::class, 'muestras'])->name('create');


    // Rutas para cursos
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/show/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Rutas para lecciones
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
});

// Ruta pública para listar cursos
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
