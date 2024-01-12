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
        Route::get('attributes', 'index');
        Route::post('attributes', 'storeWithAttr');
        Route::get('attributes/{slug}/edit', 'editWithAttr');
        Route::put('attributes/{slug}/edit', 'updateWithAttr');
        Route::get('attributes/data', 'dataAttr');
        Route::post('attribute-values', 'storeWithAttrValue');
        Route::get('attribute-values/{id}/edit', 'editWithAttrValue');
        Route::put('attribute-values/{id}/edit', 'updateWithAttrValue');
        // Route::delete('attributes/{id}', 'destroyWithAttr');
        // Route::delete('attribute-values/{id}', 'destroyWithAttrVaLue');
    });
