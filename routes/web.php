<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Views\DashboardViewController;
use App\Http\Controllers\Views\LoginViewController;
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
});

Route::get('/', function () {
    return view('templates.admin');
});
