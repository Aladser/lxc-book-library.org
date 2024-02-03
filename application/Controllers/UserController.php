<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

use function App\config;
use function App\route;

// пользователи
class UserController extends Controller
{
    private User $userModel;
    private string $csrf;
    private string $register_url;
    private string $home_url;
    private string $login_url;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->csrf = Controller::createCSRFToken();
        $this->register_url = route('register');
        $this->home_url = route('home');
        $this->login_url = route('login');
    }

    // страница авторизации
    public function login(mixed $args): void
    {
        $args['csrf'] = $this->csrf;

        // ошибки авторизации
        if (isset($args['error'])) {
            if ($args['error'] == 'wp') {
                $args['error'] = 'Неверный пароль';
            } elseif ($args['error'] == 'wu') {
                $args['error'] = 'Пользователь не существует';
            }
        } else {
            $args['user'] = '';
        }

        $routes = [
            'home' => $this->home_url,
            'register' => $this->register_url,
            'login_vk' => route('login_vk'),
            'auth' => route('auth'),
        ];

        $this->view->generate(
            page_name: "{$this->site_name} - авторизация",
            template_view: 'template_view.php',
            content_view: 'users/login_view.php',
            data: $args,
            routes: $routes,
        );
    }

    // авторизация
    public function auth(mixed $args): void
    {
        $login = $args['login'];
        $password = $args['password'];
        // проверка аутентификации
        if ($this->userModel->exists($login, 'db')) {
            // проверка введенных данных
            $isAuth = $this->userModel->is_correct_password($login, $password);
            if ($isAuth) {
                $this->saveAuth(['login' => $login], 'db');
                header("Location: {$this->home_url}");
            } else {
                header("Location: {$this->login_url}?user=$login&error=wp");
            }
        } else {
            header("Location: {$this->login_url}?user=$login&error=wu");
        }
    }

    // страница авторизации ВК
    public function login_vk()
    {
        // запрос получения ВК-кода
        $vkCodeParams = [
            'client_id' => config('VK_CLIENT_ID'),
            'redirect_uri' => config('VK_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'photos,offline',
        ];

        $get_vk_code_url = 'http://oauth.vk.com/authorize?'.http_build_query($vkCodeParams);
        header("Location: $get_vk_code_url");
    }

    // авторизация ВК
    public function auth_vk()
    {
        $authType = 'vk';
        // получение access_token
        if (isset($_GET['code'])) {
            $params = $this->getVKAccessToken($_GET['code']);
            $vkId = $params['vkid'];
            $vkToken = $params['vktoken'];
            $response = self::getVKUserInfo($vkId, $vkToken)->response;

            // обновление данных пользователя
            foreach ($response as $userItem) {
                $login = $vkId;
                $user_name = "{$userItem->first_name} {$userItem->last_name}";
                $user_photo = $userItem->photo_100;
                // добавление пользователя вк в БД, если не существует

                if ($this->userModel->exists($vkId, $authType)) {
                    $this->userModel->writeVKToken($vkId, $vkToken);
                } else {
                    $this->userModel->add(['login' => $vkId, 'token' => $vkToken], $authType);
                }

                $this->saveAuth(
                    [
                        'login' => $login,
                        'user_name' => $user_name,
                        'user_photo' => $user_photo,
                    ],
                    $authType
                );
                header('Location: '.route('home'));
            }
        }
    }

    // выйти из системы
    public function logout()
    {
        session_destroy();
        setcookie('auth_type', '', time() - 3600, '/');
        setcookie('login', '', time() - 3600, '/');
        setcookie('user_name', '', time() - 3600, '/');
        header("Location: {$this->home_url}");
    }

    // получить VKAccessToken
    private function getVKAccessToken($code)
    {
        $params = [
            'client_id' => config('VK_CLIENT_ID'),
            'client_secret' => config('VK_CLIENT_SECRET'),
            'code' => $code,
            'redirect_uri' => config('VK_REDIRECT_URI'),
        ];
        if (!$content = @file_get_contents('https://oauth.vk.com/access_token?'.http_build_query($params))) {
            $error = error_get_last();
            throw new Exception('HTTP request failed. Error: '.$error['message']);
        }

        $response = json_decode($content);
        if (isset($response->error)) {
            throw new Exception('
                    При получении токена произошла ошибка. Error: '.$response->error.'. Error description: '.$response->error_description);
        }

        return ['vktoken' => $response->access_token, 'vkid' => $response->user_id];
    }

    // страница регистрации
    public function register(mixed $args): void
    {
        $args['csrf'] = $this->csrf;
        // ошибки регистрации
        if (isset($args['error'])) {
            if ($args['error'] == 'usrexsts') {
                $args['error'] = 'Пользователь существует';
            } elseif ($args['error'] == 'system_error') {
                $args['error'] = 'Системная ошибка. Попробуйте позже';
            } elseif ($args['error'] == 'sp') {
                $args['error'] = 'Пароль не менее трех символов';
            } elseif ($args['error'] == 'dp') {
                $args['error'] = 'Пароли не совпадают';
            }
        } else {
            $args['error'] = '';
            $args['user'] = '';
        }

        $routes = [
            'login' => $this->login_url,
            'store' => route('store'),
        ];

        $this->view->generate(
            page_name: "{$this->site_name} - регистрация",
            template_view: 'template_view.php',
            content_view: 'users/register_view.php',
            data: $args,
            routes: $routes
        );
    }

    // регистрация пользователя
    public function store(mixed $args): void
    {
        $email = $args['email'];
        $password = $args['password'];

        // проверка паролей
        if ($password !== $args['password_confirm']) {
            // проверка совпадения паролей
            header("Location: {$this->register_url}?error=dp&user=$email");
        } elseif (strlen($password) < 3) {
            // длина пароля
            header("Location:{$this->register_url}?error=sp&user=$email");
        } elseif (!$this->userModel->exists($email, 'db')) {
            // регистрация пользователя
            unset($args['password_confirm']);
            $isUserRegistered = $this->userModel->add($args);
            if ($isUserRegistered) {
                $this->saveAuth(['login' => $email], 'db');
                header("Location: {$this->home_url}");
            } else {
                header("Location: {$this->register_url}?error=system_error");
            }
        } else {
            header("Location: {$this->register_url}?error=usrexsts&user=$email");
        }
    }

    // страница пользователя
    public function show()
    {
        $data = [];
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';

        $authUser = self::getAuthUser();
        $login = $authUser['login'];

        if ($authUser['auth_type'] == 'vk') {
            $token = $this->userModel->getVKToken($login);
            $response = self::getVKUserInfo($login, $token)->response;
            $data['user_login'] = "ID: {$response[0]->id}";
            $data['user_name'] = "{$response[0]->first_name} {$response[0]->last_name}";
            $data['user_photo'] = $response[0]->photo_100;
        } elseif ($authUser['auth_type'] == 'db') {
            $data['user_login'] = "Почта: $login";
            $data['user_name'] = $login;
        } else {
            return null;
        }

        $routes = [
            'home' => $this->home_url,
        ];

        $page_name = $authUser['auth_type'] === 'vk' ? "Пользователь ID$login" : "Пользователь $login";
        $this->view->generate(
            page_name: $page_name,
            template_view: 'template_view.php',
            content_view: 'users/show_view.php',
            data: $data,
            routes: $routes
        );
    }

    // получить информацию о пользователе ВК
    private static function getVKUserInfo($vkId, $vkToken)
    {
        // получить имя пользователя, фото
        $params = [
            'v' => config('VK_VERSION'),
            'user_ids' => $vkId,
            'access_token' => $vkToken,
            // Список опциональных полей https://vk.com/dev/objects/user
            'fields' => 'photo_100,about',
        ];
        if (!$content = @file_get_contents('https://api.vk.com/method/users.get?'.http_build_query($params))) {
            $error = error_get_last();
            throw new Exception('HTTP request failed. Error: '.$error['message']);
        }
        $response = json_decode($content);
        if (isset($response->error)) {
            throw new Exception($response->error);
        }

        return $response;
    }

    // получить авторизованного пользователя
    public static function getAuthUser(): mixed
    {
        $store = null;
        if (isset($_SESSION['auth_type'])) {
            $store = $_SESSION;
        } elseif (isset($_COOKIE['auth_type'])) {
            $store = $_COOKIE;
        } else {
            return false;
        }
        $userData = [
            'login' => $store['login'],
            'user_name' => $store['user_name'],
            'auth_type' => $store['auth_type'],
        ];
        if (isset($store['user_photo'])) {
            $userData['user_photo'] = $store['user_photo'];
        }

        return $userData;
    }

    // Сохранить авторизацию в куки и сессии
    private function saveAuth(array $params, $type): void
    {
        if ($type !== 'db' && $type !== 'vk') {
            throw new Exception('Неверный тип авторизации');
        }

        $_SESSION['auth_type'] = $type;
        setcookie('auth_type', $type, time() + 60 * 60 * 24, '/');
        foreach ($params as $key => $value) {
            $_SESSION[$key] = $value;
            setcookie($key, $value, time() + 60 * 60 * 24, '/');
        }
        if ($type === 'db') {
            $_SESSION['user_name'] = $params['login'];
            setcookie('user_name', $params['login'], time() + 60 * 60 * 24, '/');
        }
    }
}
