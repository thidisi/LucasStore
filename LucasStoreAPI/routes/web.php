<?php

use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
foreach (File::allFiles(__DIR__ . '/client') as $routeFile) {
    require $routeFile->getPathname();
}


