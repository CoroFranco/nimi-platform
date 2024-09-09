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
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CourseApiController;
use App\Http\Controllers\EnrollmentController;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as MiddlewareRedirectIfAuthenticated;

use function Pest\Laravel\delete;
use function Pest\Laravel\withoutMiddleware;

// Rutas públicas

Route::get('/home', [NavController::class, 'home'])->name('home');

Route::get('/', [IndexController::class, 'main'])->name('welcome')->middleware('-guest');

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


Route::get('/explorer', [NavController::class, 'explorer'])->name('explorer');
Route::get('/explorer/search', [NavController::class, 'search'])->name('api.courses.search');

Route::middleware(['-redirectInstructor'])->group(function(){
    Route::get('/become-instructor', [InstructorController::class, 'showApplicationForm'])->name('become.instructor.form');
    Route::post('/become-instructor', [InstructorController::class, 'submitApplication'])->name('become.instructor');

});

Route::middleware(['-authInstructor'])->group(function () {
    Route::get('/myCourses', [NavController::class, 'myCourses'])->name('myCourses');
    Route::get('/create', [NavController::class, 'muestras'])->name('create');
});


Route::middleware(['-auth'])->group(function () {


    Route::get('/home/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('home/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('home/profile/deleteAccount', [ProfileController::class, 'deleteAccount'])->name('delete.account');
    Route::post('home/profile/updatePassword', [ProfileController::class, 'updatePassword'])->name('update.password');

    // Rutas para inscripciones/compras

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enrollment');

    // Rutas para cursos

    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/show/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/autosave', [CourseController::class, 'autosave'])->name('courses.autosave');

    // Editar mis cursos
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');

    //rutas modulos
    Route::delete('/courses/{course}/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

    // Rutas para lecciones
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/courses/{course}/modules/{module}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');


    // Ruta para mostrar una lección específica de un curso
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])
        ->name('courses.lesson');

    // Ruta para enviar un cuestionario
    Route::post('/courses/{course}/lessons/{lesson}/submit-quiz', [LessonController::class, 'submitQuiz'])
        ->name('courses.submit-quiz');

    // Ruta para marcar un curso como completado
    Route::post('/courses/{course}/complete', [CourseController::class, 'complete'])
        ->name('courses.complete');

    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'completeLesson'])
        ->name('courses.completeLesson');


    // Rutas para comentarios
    Route::post('/courses/{course}/lessons/{lesson}/comment', [LessonController::class, 'addComment'])
        ->name('courses.addComment');

    Route::delete('/courses/{course}/lessons/{lesson}/comments/{comment}', [LessonController::class, 'deleteComment'])
        ->name('courses.deleteComment');

    Route::get('/courses/{course}/lessons/{lesson}/comments', [LessonController::class, 'getComments'])->name('courses.getComments');
});



Route::middleware(['-authAdmin'])->group(function () {
    Route::get('/admin/instructor-applications', [AdminController::class, 'instructorApplications'])->name('admin.instructor-applications');
    Route::get('/admin/instructor-applications/{application}', [AdminController::class, 'showApplication']);
    Route::post('/admin/instructor-applications/{application}/update-status', [AdminController::class, 'updateStatus']);
    Route::post('/admin/instructor-applications/{application}/update-user-role', [AdminController::class, 'updateUserRole']);
});
