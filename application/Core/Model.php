<?php

namespace App\Core;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

use function App\config;

class Model
{
    protected DBQuery $dbQuery;

    public function __construct()
    {
        $hostDB = config('HOST_DB');
        $nameDB = config('NAME_DB');
        if (!R::testConnection()) {
            R::setup("mysql:host=$hostDB;dbname=$nameDB", config('USER_DB'), config('PASS_DB'), false);
        }
    }

    // получить bean строки
    public static function find(string $table_name, string $condition, array $args): OODBBean
    {
        $respArr = R::find($table_name, $condition, $args);

        return array_values($respArr)[0];
    }
}
