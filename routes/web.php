<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Views\DashboardViewController;
use App\Http\Controllers\Views\LoginViewController;
use App\Http\Controllers\Resources\UserController;
use App\Http\Controllers\Resources\CategoryController;
use App\Http\Controllers\Resources\BlueprintController;
use App\Http\Controllers\Resources\PostController;
use App\Http\Controllers\Resources\FieldController;
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

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', LoginViewController::class)->name('login');
    Route::post('/login', AuthenticationController::class)->name('user.log');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', DashboardViewController::class)->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        Route::prefix('posts')->group(function () {
            Route::get('{category}/create', [PostController::class, 'create'])->name('posts.create');
            Route::post('{category}/store', [PostController::class, 'store'])->name('posts.store');
        });

        Route::resources([
            'users' => UserController::class,
            'categories' => CategoryController::class,
            'blueprints' => BlueprintController::class,
            'fields' => FieldController::class
        ]);
        Route::resource('posts', PostController::class)->except(['create', 'store']);
    });
});
