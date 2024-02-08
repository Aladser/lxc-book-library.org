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

    // index
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
        $new_name = $args['name'];
        $new_surname = $args['surname'];
        [$old_name, $old_surname] = explode(' ', $args['current_author_name']);

        $isExisted = $this->author->exists($new_name, $new_surname);
        if ($isExisted) {
            $response['is_updated'] = 0;
            $response['description'] = 'Указанный автор существует';
        } else {
            $isUpdated = $this->author->update($new_name, $new_surname, $old_name, $old_surname);
            if ($isUpdated) {
                $response['is_updated'] = 1;
            } else {
                $response['is_updated'] = 0;
                $response['description'] = $isUpdated;
            }
        }
        echo json_encode($response);
    }
}
