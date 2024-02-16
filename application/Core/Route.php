<?php

namespace App\Core;

use App\Controllers\MainController;
use App\Services\UserAuthService;

class Route
{
    /**
     * парсить URL.
     *
     * @param bool $specificRoutes специфичные роуты
     * @param bool $authUserRoutes роуты, требующие аутентификации
     * @param bool $adminActionArr роуты для администратора
     *
     * @return void
     */
    public static function start($specificRoutes = false, $authUserRoutes = false, $adminActionArr = false)
    {
        session_start();

        // --- проверка CSRF ---
        $csrfError = 'Невалидный CSRF';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['CSRF_JS'])) {
                if ($_POST['CSRF_JS'] !== $_SESSION['CSRF']) {
                    echo $csrfError;

                    return;
                } else {
                    unset($_POST['CSRF_JS']);
                }
            } elseif (isset($_POST['CSRF'])) {
                if ($_POST['CSRF'] !== $_SESSION['CSRF']) {
                    http_response_code(419);
                    $controller = new MainController();
                    $controller->error($csrfError);

                    return;
                } else {
                    unset($_POST['CSRF']);
                }
            } else {
                http_response_code(419);
                $controller = new MainController();
                $controller->error($csrfError);

                return;
            }
        }

        $url = mb_substr($_SERVER['REQUEST_URI'], 1);
        $url = explode('?', $url)[0];

        // аргументы функции контроллера
        $funcArgs = null;

        // --- проверка аутентифицированного пользователя ---
        if (in_array($_SERVER['REQUEST_URI'], $authUserRoutes)) {
            if (!UserAuthService::getAuthUser()) {
                header('Location: /');
            }
        }

        // --- парсинг URL ---
        if (array_key_exists($url, $specificRoutes)) {
            // проверка специфичности роутера
            $controllerName = $specificRoutes[$url];
            $action = self::convertName($url);
        } else {
            // URL - [контроллер, функция, аргумент]
            $urlAsArray = explode('/', $url);
            // получение контроллера
            $controllerName = !empty($url) ? ucfirst($urlAsArray[0]) : 'book';
            // получение функции
            $action = count($urlAsArray) > 1 ? self::convertName($urlAsArray[1]) : 'index';
            // проверка наличия аргумента
            if (count($urlAsArray) == 3) {
                $funcArgs['id'] = $urlAsArray[2];
            }
        }
        $controllerName = self::convertName($controllerName);

        // --- создаем контроллер ---
        $controllerName .= 'Controller';
        $controller_path = dirname(__DIR__, 1).DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';
        if (file_exists($controller_path)) {
            require_once $controller_path;
            $fullControllerName = '\\App\\Controllers\\'.$controllerName;
            $controller = new $fullControllerName();
        } else {
            $controller = new MainController();
            $controller->error('Страница не существует');

            return;
        }

        // --- получение аргументов ---
        // поиск POST-параметров
        if (count($_POST) > 0) {
            foreach ($_POST as $key => $value) {
                $funcArgs[$key] = htmlspecialchars($value);
            }
        }
        // поиск GET-параметров
        if (count($_GET) > 0) {
            foreach ($_GET as $key => $value) {
                $funcArgs[$key] = htmlspecialchars($value);
            }
        }

        // --- проверка прав для страниц администратора ---
        if (in_array($controllerName.' '.strtolower($action), $adminActionArr)) {
            if (!UserAuthService::isAuthAdmin()) {
                $controller = new MainController();
                $controller->error('Доступ запрещен');
            }
        }

        // --- вызов метода ---
        if (method_exists($controller, $action)) {
            $controller->$action($funcArgs);
        } else {
            $controller = new MainController();
            $controller->error('Страница не существует');
        }
    }

    // получить имя контроллера
    private static function convertName($name)
    {
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name;
    }
}
