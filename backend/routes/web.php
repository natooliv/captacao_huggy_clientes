<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/photos/{filename}', 'App\Http\Controllers\PhotoController@show')->name('photos.show');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/api/auth/google', [AuthController::class, 'authenticateWithGoogle']);


