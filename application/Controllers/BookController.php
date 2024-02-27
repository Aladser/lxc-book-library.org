<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Services\UserAuthService;

use function App\config;
use function App\route;

class BookController extends Controller
{
    private $auth_user;
    private Book $book;
    private Author $author;
    private Genre $genre;

    public function __construct()
    {
        parent::__construct();
        $this->book = new Book();
        $this->author = new Author();
        $this->genre = new Genre();

        $this->auth_user = UserAuthService::getAuthUser();
    }

    public function index(mixed $args): void
    {
        // данные шапки
        $data = self::formHeaderData();
        // серверные данные
        $data['books'] = $this->book->get_all(false);
        $data['is_admin'] = UserAuthService::isAuthAdmin();
        // роуты
        $routes = [
            'book_show' => route('book_show'),
            'book_create' => route('book_create'),
        ];
        // css
        $css_arr = [
            'book.css',
            'index.css',
        ];

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'views/book/index_view.php',
            content_css: $css_arr,
            routes: $routes,
            data: $data,
        );
    }

    public function show(mixed $args)
    {
        // данные шапки
        $data = self::formHeaderData();

        $data['is_admin'] = UserAuthService::isAuthAdmin();
        if (isset($args['error'])) {
            $data['error'] = $args['error'];
        }

        $id = $args['id'];
        $data['book'] = $this->book->get($id);
        $data['book']['picture'] = !empty($data['book']['picture']) ? $data['book']['picture'] : config('NO_IMAGE');

        // роуты
        $routes = [
            'home' => route('home'),
            'book_edit' => route('book_edit'),
            'book_delete' => route('book_delete'),
        ];

        // доп.заголовки
        $csrf = Controller::createCSRFToken();
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: $data['book']['author_name'].' - '.$data['book']['name'],
            template_view: 'template_view.php',
            content_view: 'views/book/show_view.php',
            content_css: ['book.css'],
            routes: $routes,
            data: $data,
            add_head: $csrf_meta,
        );
    }

    public function create(mixed $args)
    {
        // данные шапки
        $data = self::formHeaderData();

        $data['csrf'] = Controller::createCSRFToken();
        $data['authors'] = $this->author->get_all();
        $data['genres'] = $this->genre->get_all();

        // роуты
        $routes = [
            'book_store' => route('book_store'),
            'home' => route('home'),
        ];

        $this->view->generate(
            page_name: 'Добавление книги',
            template_view: 'template_view.php',
            content_view: 'views/book/create_view.php',
            content_css: ['form-add.css'],
            routes: $routes,
            data: $data,
        );
    }

    public function store(mixed $args)
    {
        $fields = self::parseForm($args);
        // сохранение книги
        $id = $this->book->add($fields);
        if ($id <= 0) {
            throw new \Exception('BookController->store(): ошибка сохранения книги');
        }
        self::show(['id' => $id]);
    }

    public function destroy(mixed $args)
    {
        $isRemoved = $this->book->remove($args['id']);
        if ($isRemoved) {
            header('Location: /');
        } else {
            $mainControl = new MainController();
            $mainControl->error("Серверная ошибка удаления книги. $isRemove");
        }
    }

    public function edit(mixed $args)
    {
        // данные шапки
        $data = self::formHeaderData();

        $data['csrf'] = Controller::createCSRFToken();
        $data['authors'] = $this->author->get_all();
        $data['genres'] = $this->genre->get_all();
        $data['book'] = $this->book->get($args['id']);
        $genre = $data['book']['genre'];
        $data['book']['genre'] = mb_strtolower($genre);

        // роуты
        $routes = [
            'book_update' => route('book_update'),
            'home' => route('home'),
        ];

        $this->view->generate(
            page_name: 'Редактирование книги',
            template_view: 'template_view.php',
            content_view: 'views/book/edit_view.php',
            content_css: ['form-add.css'],
            content_js: ['Classes/ServerRequest.js', 'edit_book.js'],
            routes: $routes,
            data: $data,
        );
    }

    public function update(mixed $args)
    {
        $fields = self::parseForm($args);
        // поиск существования книги
        $book_id = $this->book->get_id($fields['name'], $fields['author_id']);
        if ($book_id) {
            echo json_encode(['result' => 0, 'description' => 'Книга уже существует']);

            return;
        }
        // обновление
        $isUpdated = $this->book->update($args['id'], $fields);
        $response['result'] = (int) $isUpdated;
        if (!$isUpdated) {
            $response['description'] = 'Серверная ошибка обновления книги';
        }
        echo json_encode($response);
    }

    // парсинг формы
    private function parseForm($args): array
    {
        // поиск автора
        [$name, $surname] = explode(' ', $args['author']);
        $author_id = $this->author->get_id($name, $surname);
        if (!$author_id) {
            throw new \Exception('BookController->store(): не найден автор');
        }
        // поиск жанра
        $genre_id = $this->genre->get_id($args['genre']);
        if (!$genre_id) {
            throw new \Exception('BookController->store(): не найден жанр');
        }

        // поля строки книги
        $fields = [
            'name' => $args['name'],
            'author_id' => $author_id,
            'genre_id' => $genre_id,
            'year' => $args['year'],
        ];
        if (!empty($args['picture'])) {
            $fields['picture'] = $args['picture'];
        }
        if (!empty($args['description'])) {
            $fields['description'] = $args['description'];
        }

        return $fields;
    }
}
