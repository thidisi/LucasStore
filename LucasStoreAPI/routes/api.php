<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::post('/token', [AccessTokenController::class, 'issueToken']);

foreach (File::allFiles(__DIR__ . '/admin') as $routeFile) {
    require $routeFile->getPathname();
}
