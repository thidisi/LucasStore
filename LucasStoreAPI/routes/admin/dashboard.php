<?php
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
