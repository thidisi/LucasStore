<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')
    ->controller(CategoryController::class)
    ->group(function () {
        Route::resource('categories', CategoryController::class)->except([
            'show'
        ]);
        Route::post('categories/changeStatus/{id}', 'changStatus');
    });
