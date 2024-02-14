<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Services\UserAuthService;

use function App\route;

// пользователи
class UserController extends Controller
{
    private User $user;
    private UserAuthService $authService;
    private string $csrf;

    private string $register_url;
    private string $home_url;
    private string $login_url;
    // параметры запроса получения кода (ВК, Google)
    private array $codeParams;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->authService = new UserAuthService();
        $this->auth_user = $this->authService->getAuthUser();

        $this->register_url = route('register');
        $this->home_url = route('home');
        $this->login_url = route('login');
    }

    // страница пользователей
    public function index()
    {
        // проверка прав администратора
        $authUser = $this->authService->isAuthAdmin();
        if (!$authUser) {
            $mainControl = new MainController();
            $mainControl->error('Доступ запрещен');
        }

        // данные
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        $data['auth_user_name'] = $this->auth_user['user_name'];
        $data['auth_user_page'] = route('show');

        $csrf = Controller::createCSRFToken();
        $data['csrf'] = $csrf;
        $data['users'] = $this->user->getDBUsers();

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
    // ------------------------------------
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
                $userData = $this->user->getDBUser($login);
                $nickname = $userData['nickname'];
                $this->authService->saveAuth(['login' => $login, 'nickname' => $nickname], 'db');
                header("Location: {$this->home_url}");
            } else {
                header("Location: {$this->login_url}?user=$login&error=wp");
            }
        } else {
            header("Location: {$this->login_url}?user=$login&error=wu");
        }
    }

    // ----- АВТОРИЗАЦИЯ В СТОРОННЕМ СЕРВИСЕ -----
    // -------------------------------------------
    public function login_service($args)
    {
        $service_type = $args['id'];
        $url = $this->authService->createAuthUrl($service_type);
        header("Location: $url");
    }

    public function auth_vk()
    {
        if (!isset($_GET['code'])) {
            return;
        }

        $authType = 'vk';
        $accessTokenResponse = $this->authService->getAccessToken($_GET['code'], $authType);
        $access_token = $accessTokenResponse['access_token'];
        $user_id = $accessTokenResponse['user_id'];
        $user_name = $this->authService->getVKUserInfo($user_id, $access_token)['user_name'];

        $this->authService->saveAccessToken($authType, $access_token, $user_id, $user_name);
        $this->authService->saveAuth(['login' => $user_id, 'user_name' => $user_name], $authType);
        header('Location: '.route('home'));
    }

    public function auth_google()
    {
        if (!isset($_GET['code'])) {
            return;
        }

        $authType = 'google';
        $accessTokenResponse = $this->authService->getAccessToken($_GET['code'], $authType);
        $access_token = $accessTokenResponse['access_token'];
        $userData = $this->authService->getGoogleUserInfo($access_token);
        $user_login = $userData['user_login'];
        $user_name = $userData['user_name'];

        $this->authService->saveAccessToken($authType, $access_token, $user_login, $user_name);
        $this->authService->saveAuth(['login' => $user_login, 'user_name' => $user_name], $authType);
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
        $authUser = UserAuthService::getAuthUser();
        $login = $authUser['login'];

        // данные
        $data = [];
        $data['header_button_url'] = route('logout');
        $data['header_button_name'] = 'Выйти';
        if ($authUser['auth_type'] == 'vk' || $authUser['auth_type'] == 'google') {
            $token = $this->user->getToken($login, $authUser['auth_type']);
            if ($authUser['auth_type'] == 'vk') {
                $userData = $this->authService->getVKUserInfo($login, $token);
                $data['user_login'] = "VK_ID {$userData['user_login']}";
            } else {
                $userData = $this->authService->getGoogleUserInfo($token);
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

    // удалить пользователя
    public function destroy(mixed $args)
    {
        $isRemoved = $this->user->remove($args['user_name']);
        $response['is_removed'] = (int) $isRemoved;

        echo json_encode($response);
    }

    // разлогиниться
    public function logout()
    {
        session_destroy();
        setcookie('auth_type', '', time() - 3600, '/');
        setcookie('login', '', time() - 3600, '/');
        setcookie('user_name', '', time() - 3600, '/');
        header("Location: {$this->home_url}");
    }
}
