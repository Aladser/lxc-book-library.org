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
    private User $userModel;
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
        $this->userModel = new User();
        $this->csrf = Controller::createCSRFToken();

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

    // ----- АВТОРИЗАЦИЯ ЛОГИН-ПАРОЛЬ -----
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

    public function auth_service($args)
    {
        var_dump($args);

        return;
        if (!isset($_GET['code'])) {
            return;
        }
        $authType = $_GET['service'];
        $accessTokenResponse = self::getAccessToken($_GET['code'], $authType);
        switch ($authType) {
            case 'vk':
                $user_id = $accessTokenResponse['user_id'];
                $access_token = $accessTokenResponse['access_token'];
                $userData = self::getVKUserInfo($user_id, $access_token);
                break;
            case 'google':
                $access_token = $accessTokenResponse['access_token'];
                $this->googleClient->setAccessToken($access_token);
                $userData = self::getGoogleUserInfo($access_token);
                $user_id = $userData['user_id'];
        }
        $user_name = $userData['user_name'];
        self::saveToken($authType, $access_token, $user_id, $user_name);
        // header('Location: '.route('home'));
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

        if ($authUser['auth_type'] == 'vk' || $authUser['auth_type'] == 'google') {
            $token = $this->userModel->getToken($login, $authUser['auth_type']);
            if ($authUser['auth_type'] == 'vk') {
                $userData = self::getVKUserInfo($login, $token);
                $data['user_login'] = "VK_ID {$userData['user_login']}";
                $data['user_name'] = $userData['user_name'];
                $data['user_photo'] = $userData['user_photo'];
            } else {
                $response = self::getGoogleUserInfo($token);
                $login = $response['user_login'];
                $data['user_login'] = $response['user_login'];
                $data['user_name'] = $response['user_name'];
                $data['user_photo'] = $response['user_photo'];
            }
        } elseif ($authUser['auth_type'] == 'db') {
            $data['user_login'] = $login;
            $data['user_name'] = $login;
        } else {
            return null;
        }

        $routes = [
            'home' => $this->home_url,
        ];

        if ($authUser['auth_type'] === 'vk') {
            $page_name = 'Пользователь '.$data['user_name'];
        } else {
            $page_name = "Пользователь $login";
        }
        $this->view->generate(
            page_name: $page_name,
            template_view: 'template_view.php',
            content_view: 'user/show_view.php',
            data: $data,
            routes: $routes
        );
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

    private function saveAccessToken(string $type, string $access_token, string $user_id, string $user_name): void
    {
        // запись токена в БД
        if ($this->userModel->exists($user_id, $type)) {
            $this->userModel->writeToken($user_id, $access_token, $type);
        } else {
            $this->userModel->add(['login' => $user_id, 'token' => $access_token], $type);
        }
        $this->saveAuth(['login' => $user_id, 'user_name' => $user_name], $type);
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
    private function saveAuth(array $params, $type): void
    {
        if ($type !== 'db' && $type !== 'vk' && $type != 'google') {
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
