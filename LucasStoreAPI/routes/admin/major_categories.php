<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MajorCategoryController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')
    ->controller(MajorCategoryController::class)
    ->group(function () {
        Route::resource('major_categories', MajorCategoryController::class)->except([
            'show'
        ]);
        Route::post('major_categories/changeStatus/{id}', 'changStatus');
    });
