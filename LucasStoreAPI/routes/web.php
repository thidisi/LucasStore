<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/{pathMatch}', function () {
    abort(401, 'Unauthorized');
})->where('pathMatch', '.*');

foreach (File::allFiles(__DIR__ . '/client') as $routeFile) {
    require $routeFile->getPathname();
}
