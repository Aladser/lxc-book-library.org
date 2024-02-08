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

        // доп.заголовки
        $csrf = Controller::createCSRFToken();
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: "{$this->site_name} - авторы",
            template_view: 'template_view.php',
            content_view: 'author_view.php',
            content_css: 'author.css',
            content_js: ['ServerRequest.js', 'author.js'],
            data: $data,
            routes: $routes,
            add_head: $csrf_meta,
        );
    }

    public function update($args)
    {
        $current_author_name = $args['current_author_name'];
        $new_name = $args['name'];
        $new_surname = $args['surname'];

        $isExisted = $this->author->exists($new_name, $new_surname);
        $response = ['author_name' => "$new_name $new_surname"];
        if ($isExisted) {
            $response['is_updated'] = 0;
            $response['description'] = 'Указанный автор существует';
        } else {
            $response['is_updated'] = 1;
        }
        echo json_encode($response);
    }
}
