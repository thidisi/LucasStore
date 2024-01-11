<?php

use App\Http\Controllers\Admin\SlideController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route::group(['middleware' => ['auth:api']], function () {
//     Route::resource('major_categories', SlideController::class);
// });
Route::middleware('auth:api')
    ->controller(SlideController::class)
    ->group(function () {
        Route::resource('slides', SlideController::class)->except([
            'show'
        ]);
        Route::post('slides/changeStatus/{id}', 'changStatus');
    });
