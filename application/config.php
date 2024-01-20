<?php

namespace App;

// --- конфиг сайта ---
function config($param)
{
    $site_address = 'book-library2.local';
    // $site_address = 'd420-213-87-102-205.ngrok-free.app';
    // --- список глобальных параметров ---
    $paramList = [
        // подключение к БД
        'HOST_DB' => 'localhost',
        'NAME_DB' => 'book-library2',
        'USER_DB' => 'admin',
        'PASS_DB' => '@admin@',
        // базовый адрес страницы
        'SITE_ADDRESS' => $site_address,
        'SITE_NAME' => 'Книжная библиотека NEW',
        // вк-авторизация
        'VK_REDIRECT_URI' => "http://$site_address/user/auth_vk",
        'VK_CLIENT_ID' => 51613986,
        'VK_CLIENT_SECRET' => 'pL1sG0ctOLPHZiiPtZkj',
        'VK_VERSION' => 5.154,
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
