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

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('major_categories', MajorCategoryController::class);
});
