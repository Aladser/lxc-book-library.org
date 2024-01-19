<?php

namespace App\Core;

use function App\config;
use function App\route;

class View
{
    /**
     * создать верстку страницы.
     *
     * @param string      $page_name     имя страницы
     * @param string      $template_view путь шаблон-страницы
     * @param string      $content_view  путь контент-страницы
     * @param array|null  $data          массив данных страницы
     * @param array|null  $content_js    массив js-скриптов
     * @param string|null $content_css   путь css-файла
     * @param string|null $add_head      дополнительный head
     * @param string|null $routes        cписок роутов
     */
    public function generate(
        string $page_name,
        string $template_view,
        string $content_view,
        array $data = null,
        array $content_js = null,
        string $content_css = null,
        string $add_head = null,
        array $routes = null,
    ): void {
        // базовый адрес сайта
        $site_address = config('SITE_ADDRESS');
        $routes['logout'] = route('logout');
        $boostrap_url = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css';
        $boostrap_integrity = 'sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC';

        require_once dirname(__DIR__, 2).'/public/views/'.$template_view;
    }
}
