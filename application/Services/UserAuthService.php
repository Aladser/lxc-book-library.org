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
 * --- Сервис авторизации во внешнем сервисе ---.
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
}
