<?php

namespace App;

// --- роуты ---
function route($page_name)
{
    // --- [имя страницы => url] ---
    $urlPageList = [
        'home' => '/',
        'login' => '/login',
        'login_vk' => '/user/login_service/vk',
        'login_google' => '/user/login_service/google',
        'register' => '/register',
        'auth' => '/user/auth',
        'store' => '/user/store',
        'show' => '/user/show',
        'logout' => '/user/logout',
    ];

    try {
        if (array_key_exists($page_name, $urlPageList)) {
            return $urlPageList[$page_name];
        } else {
            throw new \Exception("Страница $page_name не существует");
        }
    } catch (\Exception $ex) {
        exit($ex);
    }
}
