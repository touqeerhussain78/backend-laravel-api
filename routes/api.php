<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['json.response']], function () {
    //Auth
	Route::post('auth/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
    Route::post('auth/register', [App\Http\Controllers\Api\AuthController::class, 'signUp'])->name('signUp');
    Route::get('auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout')
    ->middleware('auth:api');
    Route::get('auth/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword'])
    ->name('changePassword')->middleware('auth:api');

    //External api routes
    Route::get('new/articles', [App\Http\Controllers\Api\ExternalNewsController::class, 'getArticles']);
    // ->middleware('auth:api');

});
