<?php

namespace App;

use App\Core\Route;

require dirname(__DIR__, 1).'/vendor/autoload.php';

if (!file_exists(dirname(__DIR__, 1).'/logs/access.log')) {
    $rootDir = dirname(__DIR__, 1);
    exec("echo > $rootDir/logs/access.log");
    exec("echo > $rootDir/logs/error.log");
}

// специфичные роуты
//  key - действие, value - контролллер
$specificRoutes = [
    'login' => 'User',
    'register' => 'User',
    'index' => 'Book',
   ];

// роуты, требующие аутентификации
$authUserRoutes = ['/user/show', '/user/view', '/author/view', '/genre/view'];
// контроллеры и действия для url администратора
$adminActionArr = ['GenreController index', 'AuthorController index', 'UserController index', 'BookController destroy'];

Route::start($specificRoutes, $authUserRoutes, $adminActionArr);
