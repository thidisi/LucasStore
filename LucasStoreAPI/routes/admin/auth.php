<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::post('auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
