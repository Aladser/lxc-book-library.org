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
    private Book $book;
    private Author $author;
    private Genre $genre;

    private mixed $auth_user;

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
        // кнопка Войти-Выйти, данные авториз.пользователя
        if (!empty($this->auth_user)) {
            $data['header_button_name'] = 'Выйти';
            $data['header_button_url'] = route('logout');
            $data['auth_user_name'] = $this->auth_user['user_name'];
            $data['auth_user_page'] = route('show');
            if (isset($this->auth_user['user_photo'])) {
                $data['auth_user_photo'] = $this->auth_user['user_photo'];
            }
        } else {
            $data['header_button_name'] = 'Войти';
            $data['header_button_url'] = route('login');
        }
        // серверные данные
        $data['books'] = $this->book->get_all(false);
        $data['is_admin'] = UserAuthService::isAuthAdmin();
        // роуты
        $routes = [
            'book_show' => route('book_show'),
            'book_create' => route('book_create'),
        ];

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'book/index_view.php',
            content_css: ['book.css'],
            routes: $routes,
            data: $data,
        );
    }

    public function show(mixed $args)
    {
        if (!empty($this->auth_user)) {
            $data['header_button_name'] = 'Выйти';
            $data['header_button_url'] = route('logout');
            $data['auth_user_name'] = $this->auth_user['user_name'];
        } else {
            $data['header_button_name'] = 'Войти';
            $data['header_button_url'] = route('login');
        }
        $data['auth_user_page'] = route('show');
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
            content_view: 'book/show_view.php',
            content_css: ['book.css'],
            routes: $routes,
            data: $data,
            add_head: $csrf_meta,
        );
    }

    public function create(mixed $args)
    {
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
            content_view: 'book/create_view.php',
            content_css: ['form-add.css'],
            routes: $routes,
            data: $data,
        );
    }

    public function store(mixed $args)
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
            content_view: 'book/edit_view.php',
            content_css: ['form-add.css'],
            content_js: ['Classes/ServerRequest.js', 'edit_book.js'],
            routes: $routes,
            data: $data,
        );
    }

    public function update(mixed $args)
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
        // поиск существования книги
        $book_id = $this->book->get_id($args['name'], $author_id);
        if ($book_id) {
            echo json_encode(['result' => 0, 'description' => 'Книга уже существует']);

            return;
        }
        echo json_encode(['result' => 1]);
    }
}
