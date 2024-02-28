<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Genre;
use App\Services\UserAuthService;

use function App\route;

class GenreController extends Controller
{
    private array $auth_user;
    private Genre $genre;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserAuthService::getAuthUser();
        $this->genre = new Genre();
    }

    public function index(mixed $args): void
    {
        // данные шапки
        $data = self::formHeaderData();

        $csrf = Controller::createCSRFToken();
        $data['csrf'] = $csrf;
        $data['genres'] = $this->genre->get_all();

        // роуты
        $routes = [
            'show' => route('show'),
        ];

        // доп.заголовки
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: 'Жанры',
            template_view: 'template_view.php',
            content_view: 'views/admin/genre_view.php',
            content_css: ['table.css', 'form-add.css'],
            content_js: [
                'Classes/ServerRequest.js',
                'Classes/ContextMenu.js',
                'ClientControllers/ClientController.js',
                'ClientControllers/GenreClientController.js',
                'genre.js',
            ],
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
