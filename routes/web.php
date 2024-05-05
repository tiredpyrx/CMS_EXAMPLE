<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Others\TrashController;
use App\Http\Controllers\Views\DashboardViewController;
use App\Http\Controllers\Views\LoginViewController;
use App\Http\Controllers\Resources\UserController;
use App\Http\Controllers\Resources\CategoryController;
use App\Http\Controllers\Resources\PostController;
use App\Http\Controllers\Resources\FieldController;
use App\Http\Controllers\Views\ActivitiesViewController;
use App\Http\Controllers\Views\TrashViewController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', LoginViewController::class)->name('login');
    Route::post('/login', AuthenticationController::class)->name('user.log');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', DashboardViewController::class)->name('dashboard');

    Route::prefix('dashboard')->group(function () {

        Route::get('/logout', [UserController::class, 'logout'])->name('logout');
        Route::get('/trash', TrashViewController::class)->name('trash');
        Route::get('/activities', ActivitiesViewController::class)->name('activities');

        Route::group(['prefix' => 'categories', 'controller' => CategoryController::class], function () {
            Route::patch('/{modelName}/active', 'updateActive')->name('categories.active');

            Route::delete('/delete/all/selected', 'deleteAllSelected')->name('categories.deleteAllSelected');
            Route::delete('/unactives/delete', 'deleteAllUnactives')->name('categories.deleteAllUnactives');
            Route::delete('{category}/unactives/children/delete', 'deleteAllUnactiveChildren')->name('categories.deleteAllUnactiveChildren');

            Route::patch('{category}/icon', 'updateIcon')->name('categories.update.icon');

            Route::patch('/order/update', 'updateOrder')->name('categories.updateOrder');
        });

        Route::group(['prefix' => 'posts', 'controller' => PostController::class], function () {
            Route::get('{category}/create', 'create')->name('posts.create');
            Route::post('{category}/store', 'store')->name('posts.store');

            Route::delete('/delete/all/selected', 'deleteAllSelected')->name('posts.deleteAllSelected');
            Route::delete('/unactives/delete', 'deleteAllUnactives')->name('posts.deleteAllUnactives');
        });

        Route::group(['prefix' => 'fields', 'controller' => FieldController::class], function () {
            Route::get('{category}/create', 'create')->name('fields.create');
            Route::post('{category}/store', 'store')->name('fields.store');

            Route::patch('/{modelName}/active', 'updateActive')->name('fields.active');
        });

        Route::group(['prefix' => 'users', 'controller' => UserController::class], function () {
            Route::get('{user}/actions', 'actions')->name('users.actions');

            // all activites, login times, logout times, logged browsers, logged devices, everything
            // we can use https://github.com/shetabit/visitor
            Route::get('{user}/activites', 'activites')->name('users.activites');
        });

        Route::group(['prefix' => 'trash', 'controller' => TrashController::class], function () {
        });


        Route::resources([
            'users' => UserController::class,
            'categories' => CategoryController::class,
            'files' => FileController::class,
        ]);
        Route::resource('posts', PostController::class)->except(['create', 'store']);
        Route::resource('fields', FieldController::class)->except(['create', 'store']);
    });
});


Route::get('{slug?}', FrontController::class);
