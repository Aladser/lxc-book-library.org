<?php

namespace App\Core;

use function App\config;

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
}
