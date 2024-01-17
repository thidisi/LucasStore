<?php

use App\Http\Controllers\Admin\AboutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')
    ->controller(AboutController::class)
    ->group(function () {
        Route::resource('about', AboutController::class)->only([
            'index'
        ]);
        Route::post('about', 'save');
    });
