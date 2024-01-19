<?php

namespace App;

// --- конфиг сайта ---
function config($param)
{
    // --- список глобальных параметров ---
    $paramList = [
        // подключение к БД
        'HOST_DB' => 'localhost',
        'NAME_DB' => 'book-library2',
        'USER_DB' => 'admin',
        'PASS_DB' => '@admin@',
        // базовый адрес страницы
        'SITE_ADDRESS' => 'book-library2.local',
        'SITE_NAME' => 'Книжная библиотека NEW',
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
