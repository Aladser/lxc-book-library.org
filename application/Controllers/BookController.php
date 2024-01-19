<?php

namespace App\Controllers;

use App\Core\Controller;

use function App\route;

class BookController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(mixed $args): void
    {
        $routes = [
            'login' => route('login'),
        ];

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'index_view.php',
            content_css: 'index.css',
            routes: $routes,
        );
    }
}
