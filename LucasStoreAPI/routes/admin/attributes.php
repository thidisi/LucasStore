<?php

use App\Http\Controllers\Admin\AttributeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')
    ->controller(AttributeController::class)
    ->group(function () {
        Route::resource('attributes', AttributeController::class)->except([
            'show',
        ]);
        Route::post('attributes/changeStatus/{id}', 'changeStatus');
    });
