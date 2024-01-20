<?php

namespace App;

// --- конфиг сайта ---
function config($param)
{
    $site_address = 'book-library';
    // $site_address = 'd420-213-87-102-205.ngrok-free.app';
    // --- список глобальных параметров ---
    $paramList = [
        // подключение к БД
        'HOST_DB' => 'localhost',
        'NAME_DB' => $site_address,
        'USER_DB' => 'admin',
        'PASS_DB' => '@admin@',
        // базовый адрес страницы
        'SITE_ADDRESS' => $site_address.'.local',
        'SITE_NAME' => 'Книжная библиотека',
        // вк-авторизация
        'VK_REDIRECT_URI' => "http://$site_address.local/user/auth_vk",
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
