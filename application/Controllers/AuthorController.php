<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Author;
use App\Services\UserAuthService;

use function App\route;

class AuthorController extends Controller
{
    private array $auth_user;
    private Author $author;

    public function __construct()
    {
        parent::__construct();
        $this->auth_user = UserAuthService::getAuthUser();
        $this->author = new Author();
    }

    public function index(mixed $args): void
    {
        // данные
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        $data['auth_user_name'] = $this->auth_user['user_name'];
        $data['auth_user_page'] = route('show');

        $csrf = Controller::createCSRFToken();
        $data['csrf'] = $csrf;
        $data['authors'] = $this->author->get_all();

        // роуты
        $routes = [
            'show' => route('show'),
        ];

        // доп.заголовки
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: 'Авторы',
            template_view: 'template_view.php',
            content_view: 'views/admin/author_view.php',
            content_css: ['table.css', 'form-add.css'],
            content_js: [
                'Classes/ServerRequest.js',
                'ClientControllers/ClientController.js',
                'ClientControllers/AuthorClientController.js',
                'author.js',
            ],
            data: $data,
            routes: $routes,
            add_head: $csrf_meta,
        );
    }

    public function store($args)
    {
        [$name, $surname] = [$args['name'], $args['surname']];
        $isExisted = $this->author->exists($name, $surname);
        if ($isExisted) {
            $response['is_added'] = 0;
            $response['description'] = 'Указанный автор существует';
        } else {
            $id = $this->author->add($name, $surname);
            $response['is_added'] = $id;
        }
        echo json_encode($response);
    }

    public function update($args)
    {
        $new_name = $args['name'];
        $new_surname = $args['surname'];
        [$old_name, $old_surname] = explode(' ', $args['current_author_name']);

        $isExisted = $this->author->exists($new_name, $new_surname);
        if ($isExisted) {
            $response['is_updated'] = 0;
            $response['description'] = 'Указанный автор существует';
        } else {
            $isUpdated = $this->author->update($new_name, $new_surname, $old_name, $old_surname);
            if ($isUpdated === true) {
                $response['is_updated'] = 1;
            } else {
                $response['is_updated'] = 0;
                $response['description'] = $isUpdated;
            }
        }
        echo json_encode($response);
    }

    public function destroy($args)
    {
        [$name, $surname] = explode(' ', $args['author_name']);
        $isRemoved = $this->author->remove($name, $surname);
        $response['is_removed'] = (int) $isRemoved;

        echo json_encode($response);
    }
}
