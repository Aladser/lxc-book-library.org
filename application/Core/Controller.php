<?php

namespace App\Core;

use App\Services\UserAuthService;

use function App\config;
use function App\route;

abstract class Controller
{
    public View $view;
    protected string $site_name;

    public function __construct()
    {
        $this->site_name = config('SITE_NAME');
        $this->view = new View();
    }

    /** создать CSRF-токен.
     * @throws \Exception
     */
    public static function createCSRFToken(): string
    {
        $csrfToken = hash('gost-crypto', random_int(0, 999999));
        $_SESSION['CSRF'] = $csrfToken;

        return $csrfToken;
    }

    //  сформировать данные шапки сайта
    public function formHeaderData()
    {
        $auth_user = UserAuthService::getAuthUser();
        if (!empty($auth_user)) {
            $data['header_button_name'] = 'Выйти';
            $data['header_button_url'] = route('logout');
            $data['auth_user_name'] = $auth_user['user_name'];
            $data['auth_user_page'] = route('show');
        } else {
            $data['header_button_name'] = 'Войти';
            $data['header_button_url'] = route('login');
        }

        return $data;
    }
}
