<?php

namespace App\Controllers;

use App\Core\Controller;

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
        $this->view->generate(
            'Ошибка',
            'template_view.php',
            'page_error_view.php',
            $data
        );
    }
}
