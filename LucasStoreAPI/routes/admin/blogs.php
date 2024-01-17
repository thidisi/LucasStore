<?php

use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')
    ->controller(BlogController::class)
    ->group(function () {
        Route::resource('blogs', BlogController::class)->except([
            'show','create'
        ]);
        Route::post('blogs/changeStatus/{id}', 'changeStatus');
    });
