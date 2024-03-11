<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\FrontController;
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
        Route::prefix('categories')->group(function () {
            Route::patch('/{modelName}/active', [CategoryController::class, 'updateActive'])->name('categories.active');

            Route::delete('/delete/all/selected', [CategoryController::class, 'deleteAllSelected'])->name('categories.deleteAllSelected');
            Route::delete('/unactives/delete', [CategoryController::class, 'deleteAllUnactives'])->name('categories.deleteAllUnactives');
            Route::delete('{category}/unactives/children/delete', [CategoryController::class, 'deleteAllUnactiveChildren'])->name('categories.deleteAllUnactiveChildren');
        });

        Route::prefix('posts')->group(function () {
            Route::get('{category}/create', [PostController::class, 'create'])->name('posts.create');
            Route::post('{category}/store', [PostController::class, 'store'])->name('posts.store');

            Route::delete('/delete/all/selected', [PostController::class, 'deleteAllSelected'])->name('posts.deleteAllSelected');
            Route::delete('/unactives/delete', [PostController::class, 'deleteAllUnactives'])->name('posts.deleteAllUnactives');
        });

        Route::group(['prefix' => 'fields', 'controller' => FieldController::class], function () {
            Route::get('{modelName}/{modelId}/create', 'create')->name('fields.create');
            Route::post('{modelName}/{modelId}/store', 'store')->name('fields.store');

            Route::patch('/{modelName}/active', 'updateActive')->name('fields.active');
        });

        Route::resources([
            'users' => UserController::class,
            'categories' => CategoryController::class,
            'blueprints' => BlueprintController::class,
        ]);
        Route::resource('posts', PostController::class)->except(['create', 'store']);
        Route::resource('fields', FieldController::class)->except(['create', 'store']);
    });
});


Route::get('{slug}', FrontController::class);