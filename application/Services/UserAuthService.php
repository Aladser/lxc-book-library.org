<?php

namespace App\Services;

use App\Models\User;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

use function App\config;

/**
 * ----------------------------
 * --- Сервис авторизации -----
 * ----------------------------.
 */
class UserAuthService
{
    private User $user;
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
        $this->user = new User();

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

    // создать ссылку для атворизации
    public function createAuthUrl(string $service_type): string
    {
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
                throw new Exception('HTTP request failed: неверный тип сервиса авторизации');
        }

        return $url;
    }

    // получить access_token
    public function getAccessToken(string $code, string $serviceName): array
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

    // сохранить access_token
    public function saveAccessToken(string $authType, string $access_token, string $user_id, string $user_name): void
    {
        // проверить тип авторизации
        if ($authType !== 'db' && $authType !== 'vk' && $authType != 'google') {
            throw new Exception('Неверный тип авторизации');
        }

        if ($this->user->exists($user_id, $authType)) {
            $this->user->writeToken($user_id, $access_token, $authType);
        } else {
            $this->user->add(['login' => $user_id, 'token' => $access_token], $authType);
        }
    }

    // данные  пользователя ВК
    public function getVKUserInfo(string $user_id, string $access_token): array
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

    // данные пользователя Google
    public function getGoogleUserInfo(string $access_token)
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
    public function saveAuth(array $params, $authType): void
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
            $user_name = !empty($params['nickname']) ? $params['nickname'] : $params['login'];
            $_SESSION['user_name'] = $user_name;
            setcookie('user_name', $user_name, time() + 60 * 60 * 24, '/');
        }
    }

    // проверка прав администратора
    public function isAuthAdmin()
    {
        $auth_user = self::getAuthUser();

        if (!$auth_user) {
            return false;
        }

        if ($auth_user['auth_type'] == 'google') {
            return false;
        } else {
            $userData = $this->user->getDBUser($auth_user['login']);
            if ($userData['is_admin'] == 0) {
                return false;
            }

            return true;
        }
    }
}
