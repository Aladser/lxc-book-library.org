<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Author;

use function App\route;

class AuthorController extends Controller
{
    private mixed $auth_user;
    private Author $author;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserController::getAuthUser();
        $this->author = new Author();
    }

    public function view(mixed $args): void
    {
        // данные
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        $data['authors'] = $this->author->get();

        // роуты
        $routes = [
            'show' => route('show'),
        ];

        $this->view->generate(
            page_name: "{$this->site_name} - авторы",
            template_view: 'template_view.php',
            content_view: 'author_view.php',
            content_css: 'admin_page.css',
            data: $data,
            routes: $routes,
        );
    }
}
