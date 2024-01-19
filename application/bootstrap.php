<?php

namespace App;

use App\Core\Route;

require dirname(__DIR__, 1).'/vendor/autoload.php';

if (!file_exists(dirname(__DIR__, 1).'/logs/access.log')) {
    $rootDir = dirname(__DIR__, 1);
    exec("echo > $rootDir/logs/access.log");
    exec("echo > $rootDir/logs/error.log");
}

Route::start();
