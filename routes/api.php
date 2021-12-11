<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\IBController;
use App\Http\Controllers\Api\v1\UserController;
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

Route::group([
    'prefix' => 'v1'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');

    Route::post('user/add', [UserController::class, 'store'])->name('user.store');

    Route::group(['prefix' => 'ib'], function () {
        Route::post('updateTotalBalance', [IBController::class, 'updateTotalBalance'])->name('ib.updateTotalBalance');
    });
});
