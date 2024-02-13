<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\UserAuthService;

use function App\route;

class BookController extends Controller
{
    private mixed $auth_user;
    private UserAuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new UserAuthService();
        $this->auth_user = $this->authService->getAuthUser();
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

        $this->view->generate(
            page_name: $this->site_name,
            template_view: 'template_view.php',
            content_view: 'book/index_view.php',
            content_css: ['index.css'],
            data: $data,
        );
    }

    public function create()
    {
    }

    public function show()
    {
    }
}
