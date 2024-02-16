<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Services\UserAuthService;

use function App\config;
use function App\route;

class BookController extends Controller
{
    private Book $book;
    private mixed $auth_user;
    private UserAuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->book = new Book();
        $this->authService = new UserAuthService();
        $this->auth_user = $this->authService->getAuthUser();
    }

    // список книг
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
        $userAuthService = new UserAuthService();
        $data['is_admin'] = $userAuthService->isAuthAdmin();
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

    // страница книги
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

        $userAuthService = new UserAuthService();
        $data['is_admin'] = $userAuthService->isAuthAdmin();

        $id = $args['id'];
        $data['book'] = $this->book->get($id);
        $data['book']['picture'] = !empty($data['book']['picture']) ? $data['book']['picture'] : config('NO_IMAGE');

        // роуты
        $routes = [
            'home' => route('home'),
            'book_delete' => route('book_delete'),
        ];

        // доп.заголовки
        $csrf = Controller::createCSRFToken();
        $csrf_meta = "<meta name='csrf' content=$csrf>";

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'book/show_view.php',
            content_css: ['book.css'],
            routes: $routes,
            data: $data,
            add_head: $csrf_meta,
        );
    }

    // форма добавления книги
    public function create(mixed $args)
    {
        $data['csrf'] = Controller::createCSRFToken();

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

    // удаление книги
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

    public function store(mixed $args)
    {
        var_dump($args);
    }
}
