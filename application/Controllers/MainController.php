<?php

namespace App\Controllers;

use App\Core\Controller;

use function App\route;

/** общий контроллер */
class MainController extends Controller
{
    public function error($errorName)
    {
        $data = self::formHeaderData();
        $data = ['error' => $errorName];

        $routes = [
            'home' => route('home'),
        ];

        $this->view->generate(
            page_name: 'Ошибка',
            template_view: 'template_view.php',
            content_view: 'views/page_error_view.php',
            routes: $routes,
            data: $data
        );
    }
}
