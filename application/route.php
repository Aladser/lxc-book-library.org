<?php

namespace App;

// --- роуты ---
function route($param)
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
        'users' => '/user',
        'authors' => '/author',
        'genres' => '/genre',

        'book_show' => '/book/show/',
        'book_delete' => '/book/destroy/',
        'book_create' => '/book/create',
        'book_store' => '/book/store',
        'book_edit' => '/book/edit/',
        'book_update' => '/book/update',
    ];

    try {
        if (array_key_exists($param, $urlPageList)) {
            return $urlPageList[$param];
        } else {
            throw new \Exception("route($param): параметр $param не существует");
        }
    } catch (\Exception $ex) {
        exit($ex);
    }
}
