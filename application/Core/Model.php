<?php

namespace App\Core;

use function App\config;

class Model
{
    protected DBQuery $dbQuery;

    public function __construct()
    {
        $hostDB = config('HOST_DB');
        $nameDB = config('NAME_DB');
        $this->dbQuery = new DBQuery($hostDB, $nameDB, config('USER_DB'), config('PASS_DB'));

        // RedBean
        if (!\RedBeanPHP\R::testConnection()) {
            \RedBeanPHP\R::setup("mysql:host=$hostDB;dbname=$nameDB", config('USER_DB'), config('PASS_DB'), false);
        }
    }
}
