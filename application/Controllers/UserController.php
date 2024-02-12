<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

use function App\config;
use function App\route;

// пользователи
class UserController extends Controller
{
    private User $user;
    private string $csrf;

    private string $register_url;
    private string $home_url;
    private string $login_url;
    // параметры запроса получения кода (ВК, Google)
    private array $codeParams;
    // ВК авторизация
    private VKApiClient $vkApiClient;
    private VKOAuth $vkOAuth;
    // Google авторизация
    private \Google\Client $googleClient;
    private \Google\Service\Oauth2 $google_oauth;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->auth_user = UserController::getAuthUser();

        $this->register_url = route('register');
        $this->home_url = route('home');
        $this->login_url = route('login');

        $this->vkApiClient = new VKApiClient();
        $this->vkOAuth = new VKOAuth();

        $this->googleClient = new \Google\Client();
        $this->googleClient->setClientId(config('GOOGLE_CLIENT_ID'));
        $this->googleClient->setClientSecret(config('GOOGLE_CLIENT_SECRET'));
        $this->googleClient->setRedirectUri(config('GOOGLE_REDIRECT_URI'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->google_oauth = new \Google\Service\Oauth2($this->googleClient);
    }

    public function view()
    {
        // данные
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        $data['auth_user_name'] = $this->auth_user['user_name'];
        $data['auth_user_page'] = route('show');

        $data['users'] = $this->user->getDBUsers();
        $csrf = Controller::createCSRFToken();
        $data['csrf'] = $csrf;

        // роуты
        $routes = [
            'show' => route('show'),
            'store' => route('store'),
        ];

        // доп.заголовки
        $csrf_meta = "<meta name='csrf' content={$csrf}>";

        $this->view->generate(
            page_name: "{$this->site_name} - пользователи",
            template_view: 'template_view.php',
            content_view: 'admin/users_view.php',
            content_css: ['context_menu.css', 'table.css', 'form-add.css'],
            content_js: [
                'Classes/ServerRequest.js',
                'Classes/ContextMenu.js',
                'ClientControllers/ClientController.js',
                'ClientControllers/UserClientController.js',
                'users.js',
            ],
            data: $data,
            routes: $routes,
            add_head: $csrf_meta,
        );
    }

    // ----- АВТОРИЗАЦИЯ ЛОГИН-ПАРОЛЬ -----
    public function login(mixed $args): void
    {
        $args['csrf'] = Controller::createCSRFToken();

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
            'login_google' => route('login_google'),
            'auth' => route('auth'),
        ];

        $this->view->generate(
            page_name: "{$this->site_name} - авторизация",
            template_view: 'template_view.php',
            content_view: 'user/login_view.php',
            data: $args,
            routes: $routes,
        );
    }

    public function auth(mixed $args): void
    {
        $login = $args['login'];
        $password = $args['password'];
        // проверка аутентификации
        if ($this->user->exists($login, 'db')) {
            // проверка введенных данных
            $isAuth = $this->user->is_correct_password($login, $password);
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

    // ----- АВТОРИЗАЦИЯ В СТОРОННЕМ СЕРВИСЕ -----
    public function login_service($args)
    {
        $service_type = $args['id'];
        switch ($service_type) {
            case 'vk':
                $url = $this->vkOAuth->getAuthorizeUrl(
                    VKOAuthResponseType::CODE,
                    config('VK_CLIENT_ID'),
                    config('VK_REDIRECT_URI'),
                    VKOAuthDisplay::PAGE,
                    [VKOAuthUserScope::PHOTOS, VKOAuthUserScope::OFFLINE],
                    config('VK_CLIENT_SECRET')
                );
                break;
            case 'google':
                $url = $this->googleClient->createAuthUrl();
                break;
            default:
                throw new \Exception('HTTP request failed: неверный тип сервиса авторизации');
        }

        header("Location: $url");
    }

    public function auth_vk()
    {
        if (!isset($_GET['code'])) {
            return;
        }
        $authType = 'vk';

        $accessTokenResponse = self::getAccessToken($_GET['code'], $authType);
        $access_token = $accessTokenResponse['access_token'];
        $user_id = $accessTokenResponse['user_id'];

        $userData = self::getVKUserInfo($user_id, $access_token);
        $user_name = $userData['user_name'];

        self::saveAccessToken($authType, $access_token, $user_id, $user_name);
        header('Location: '.route('home'));
    }

    public function auth_google()
    {
        if (!isset($_GET['code'])) {
            return;
        }
        $authType = 'google';

        $accessTokenResponse = self::getAccessToken($_GET['code'], $authType);
        $access_token = $accessTokenResponse['access_token'];

        $this->googleClient->setAccessToken($access_token);
        $userData = self::getGoogleUserInfo($access_token);
        $user_login = $userData['user_login'];
        $user_name = $userData['user_name'];

        self::saveAccessToken($authType, $access_token, $user_login, $user_name);
        header('Location: '.route('home'));
    }

    // страница регистрации
    public function register(mixed $args): void
    {
        $args['csrf'] = Controller::createCSRFToken();
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
            content_view: 'user/register_view.php',
            data: $args,
            routes: $routes
        );
    }

    // регистрация пользователя
    public function store(mixed $args): void
    {
        $email = $args['email'];
        $password = $args['password'];
        $isAdmin = isset($args['is_admin']) ? $args['is_admin'] == 1 : false;

        if (isset($args['password_confirm'])) {
            // --- регистрация пользователем ---

            // проверка паролей
            if ($password !== $args['password_confirm']) {
                // проверка совпадения паролей
                header("Location: {$this->register_url}?error=dp&user=$email");
            } elseif (strlen($password) < 3) {
                // длина пароля
                header("Location:{$this->register_url}?error=sp&user=$email");
            } elseif (!$this->user->exists($email, 'db')) {
                // регистрация пользователя
                unset($args['password_confirm']);
                $isUserRegistered = $this->user->add($args);
                if ($isUserRegistered) {
                    $this->saveAuth(['login' => $email], 'db');
                    header("Location: {$this->home_url}");
                } else {
                    header("Location: {$this->register_url}?error=system_error");
                }
            } else {
                header("Location: {$this->register_url}?error=usrexsts&user=$email");
            }
        } else {
            // --- регистрация администратором ---

            if (!$this->user->exists($email, 'db')) {
                $isAdded = $this->user->add($args);

                echo json_encode(['is_added' => $isAdded]);
            } else {
                echo json_encode(['is_added' => 0, 'description' => 'Пользователь существует']);
            }
        }
    }

    // страница пользователя
    public function show()
    {
        $authUser = self::getAuthUser();
        $login = $authUser['login'];

        // данные
        $data = [];
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        if ($authUser['auth_type'] == 'vk' || $authUser['auth_type'] == 'google') {
            $token = $this->user->getToken($login, $authUser['auth_type']);
            if ($authUser['auth_type'] == 'vk') {
                $userData = self::getVKUserInfo($login, $token);
                $data['user_login'] = "VK_ID {$userData['user_login']}";
            } else {
                $userData = self::getGoogleUserInfo($token);
                $login = $userData['user_login'];
                $data['user_login'] = $userData['user_login'];
            }
            $data['user_name'] = $userData['user_name'];
            $data['user_photo'] = $userData['user_photo'];
            $isAdmin = false;
        } elseif ($authUser['auth_type'] == 'db') {
            $userData = $this->user->getDBUser($login);
            $data['user_login'] = $login;
            $data['user_name'] = $userData['nickname'];
            $isAdmin = $userData['is_admin'] == 1;
        } else {
            return null;
        }

        // роуты
        $routes = [
            'home' => $this->home_url,
            'authors' => route('authors'),
            'genres' => route('genres'),
            'users' => route('users'),
        ];

        // название страницы

        // контент страницы
        if ($isAdmin) {
            $page_name = "{$this->site_name} - администрирование";
            $content_view = 'admin/admin_view.php';
            $content_css = ['admin_page.css'];
        } else {
            $page_name = 'Пользователь ';
            $page_name .= $authUser['auth_type'] === 'vk' ? $data['user_name'] : $login;
            $content_view = 'user/show_view.php';
            $content_css = null;
        }

        $this->view->generate(
            page_name: $page_name,
            template_view: 'template_view.php',
            content_view: $content_view,
            data: $data,
            routes: $routes,
            content_css: $content_css
        );
    }

    public function update(mixed $args)
    {
        var_dump($args);
    }

    public function destroy(mixed $args)
    {
        $isRemoved = $this->user->remove($args['user_name']);
        $response['is_removed'] = (int) $isRemoved;

        echo json_encode($response);
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

    // получить access_token
    private function getAccessToken(string $code, string $serviceName): array
    {
        switch ($serviceName) {
            case 'vk':
                return $this->vkOAuth->getAccessToken(
                    config('VK_CLIENT_ID'),
                    config('VK_CLIENT_SECRET'),
                    config('VK_REDIRECT_URI'),
                    $code
                );
            case 'google':
                return $this->googleClient->fetchAccessTokenWithAuthCode($code);
            default:
                throw new \Exception('UserController::getAccessToken(): неверный тип сервиса');
        }
    }

    private function saveAccessToken(string $authType, string $access_token, string $user_id, string $user_name): void
    {
        // запись токена в БД
        if ($this->user->exists($user_id, $authType)) {
            $this->user->writeToken($user_id, $access_token, $authType);
        } else {
            $this->user->add(['login' => $user_id, 'token' => $access_token], $authType);
        }
        $this->saveAuth(['login' => $user_id, 'user_name' => $user_name], $authType);
    }

    // данные  пользователя ВК
    private function getVKUserInfo(string $user_id, string $access_token): array
    {
        $userDataResponse = $this->vkApiClient->users()->get($access_token, [
            'user_ids' => $user_id,
            'fields' => ['photo_100'],
        ])[0];
        $user_name = "{$userDataResponse['first_name']} {$userDataResponse['last_name']}";

        return [
            'user_login' => $userDataResponse['id'],
            'user_name' => $user_name,
            'user_photo' => $userDataResponse['photo_100'],
        ];
    }

    // данные пользователя Gmail
    private function getGoogleUserInfo(string $access_token)
    {
        $this->googleClient->setAccessToken($access_token);
        $google_account_info = $this->google_oauth->userinfo->get();

        return [
            'user_login' => $google_account_info->email,
            'user_name' => $google_account_info->name,
            'user_photo' => $google_account_info->picture,
        ];
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

        return $userData;
    }

    // Сохранить авторизацию в куки и сессии
    private function saveAuth(array $params, $authType): void
    {
        if ($authType !== 'db' && $authType !== 'vk' && $authType != 'google') {
            throw new Exception('Неверный тип авторизации');
        }

        $_SESSION['auth_type'] = $authType;
        setcookie('auth_type', $authType, time() + 60 * 60 * 24, '/');
        foreach ($params as $key => $value) {
            $_SESSION[$key] = $value;
            setcookie($key, $value, time() + 60 * 60 * 24, '/');
        }
        if ($authType === 'db') {
            $_SESSION['user_name'] = $params['login'];
            setcookie('user_name', $params['login'], time() + 60 * 60 * 24, '/');
        }
    }
}
