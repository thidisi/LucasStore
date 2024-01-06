<?php
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::post('auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout']);
