<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Genre;

use function App\route;

class GenreController extends Controller
{
    private mixed $auth_user;
    private Genre $genre;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserController::getAuthUser();
        $this->genre = new Genre();
    }

    public function view(mixed $args): void
    {
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        $data['genres'] = $this->genre->get();
        $csrf = Controller::createCSRFToken();
        $data['csrf'] = $csrf;

        $routes = [
            'show' => route('show'),
        ];

        $this->view->generate(
            page_name: "{$this->site_name} - жанры",
            template_view: 'template_view.php',
            content_view: 'genre_view.php',
            data: $data,
            routes: $routes,
        );
    }
}
