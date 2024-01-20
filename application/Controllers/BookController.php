<?php

namespace App\Controllers;

use App\Core\Controller;

use function App\route;

class BookController extends Controller
{
    private string $auth_user;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserController::getAuthUser();
    }

    public function index(mixed $args): void
    {
        if (empty($this->auth_user)) {
            $data['header_button_name'] = 'Войти';
            $data['header_button_url'] = route('login');
        } else {
            $data['header_button_name'] = 'Выйти';
            $data['header_button_url'] = route('logout');
        }
        $data['auth_user'] = $this->auth_user;

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'index_view.php',
            content_css: 'index.css',
            data: $data,
        );
    }
}
