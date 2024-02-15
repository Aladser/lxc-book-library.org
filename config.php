<?php

namespace App;

// --- конфиг сайта ---
function config($param)
{
    $site_name = 'lxc-book-library';
    $site_address = $site_name.'.org';
    // $site_name = 'd420-213-87-102-205.ngrok-free.app';
    // --- список глобальных параметров ---
    $paramList = [
        // подключение к БД
        'HOST_DB' => 'localhost',
        'NAME_DB' => $site_name,
        'USER_DB' => 'admin',
        'PASS_DB' => '@admin@',
        // базовый адрес страницы
        'SITE_ADDRESS' => $site_address,
        'SITE_NAME' => 'Книжная библиотека',
        // вк-авторизация
        'VK_REDIRECT_URI' => "http://$site_address/user/auth_vk",
        'VK_CLIENT_ID' => 51613986,
        'VK_CLIENT_SECRET' => 'pL1sG0ctOLPHZiiPtZkj',
        // google-авторизация
        'GOOGLE_REDIRECT_URI' => "http://$site_address/user/auth_google",
        'GOOGLE_CLIENT_ID' => '682336466170-iab6q6gonoi2qm7rvipgph7f4enk0tbi.apps.googleusercontent.com',
        'GOOGLE_CLIENT_SECRET' => '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',
        // no image изображение
        'NO_IMAGE' => '/public/images/no-image.png',
    ];

    try {
        if (array_key_exists($param, $paramList)) {
            return $paramList[$param];
        } else {
            throw new \Exception("Параметр $param не существует");
        }
    } catch (\Exception $ex) {
        exit($ex);
    }
}
