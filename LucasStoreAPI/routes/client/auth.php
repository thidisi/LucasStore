<?php
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:api', 'cors', 'modify.accept']], function () {
});
