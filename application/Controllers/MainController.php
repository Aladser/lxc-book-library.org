<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\UserAuthService;

use function App\route;

/** контрллер главной страницы */
class MainController extends Controller
{
    public function index()
    {
        // роуты
        $routes = [
            'register' => route('register'),
            'login' => route('login'),
        ];

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'main_view.php',
            routes: $routes
        );
    }

    public function error($errorName)
    {
        $data = ['error' => $errorName];
        $data['header_button_name'] = 'Выйти';
        $data['header_button_url'] = route('logout');

        $authService = new UserAuthService();
        $auth_user = $authService->getAuthUser();
        if (isset($data['auth_user_name'])) {
            $data['auth_user_name'] = $auth_user['user_name'];
        }
        $data['auth_user_page'] = route('show');

        // роуты
        $routes = [
            'home' => route('home'),
        ];

        $this->view->generate(
            page_name: 'Ошибка',
            template_view: 'template_view.php',
            content_view: 'page_error_view.php',
            routes: $routes,
            data: $data
        );
    }
}
