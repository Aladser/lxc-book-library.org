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

        // доп.заголовки
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: "{$this->site_name} - жанры",
            template_view: 'template_view.php',
            content_view: 'genre_view.php',
            content_js: ['ServerRequest.js', 'ClientControllers/GenreClientController.js', 'genre.js'],
            content_css: 'genre.css',
            data: $data,
            routes: $routes,
            add_head: $csrf_meta,
        );
    }

    public function store($args)
    {
        $isExisted = $this->genre->exists($args['name']);
        if ($isExisted) {
            $response['is_added'] = 0;
            $response['description'] = 'Указанный автор существует';
        } else {
            $id = $this->genre->add($args['name']);
            $response['is_added'] = $id;
        }
        echo json_encode($response);
    }

    public function destroy($args)
    {
        $isRemoved = $this->genre->remove($args['genre_name']);
        $response['is_removed'] = $isRemoved;

        echo json_encode($response);
    }
}
