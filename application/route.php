<?php

namespace App;

// --- роуты ---
function route($page_name) {
    // --- [имя страницы => url] ---
    $urlPageList = [
        'home' => '/',
        'login' => '/login',
        'register' => '/register',
        'auth' => '/user/auth',
        'store' => '/user/store',
        'article' => '/article', 
        'article_create' => '/article/create',
        'article_show' => '/article/show',
        'article_store' => '/article/store',
        'article_edit' => '/article/edit',
        'article_remove' => '/article/remove',
        'article_update' => '/article/update',
        'logout' => '/main?logout=true',
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
