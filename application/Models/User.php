<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** таблица пользователей */
class User extends Model
{
    // id сервисов авторизации в БД
    private array $authServiceIds;
    private string $dbTableName = 'db_users';
    private string $authServiceUsers = 'auth_service_users';

    public function __construct()
    {
        parent::__construct();
        $this->authServiceIds = ['vk' => 1, 'google' => 2];

        R::ext('xdispense', function ($type) {
            return R::getRedBean()->dispense($type);
        });
    }

    // ---получить пользователей внутренней авторизации---
    public function getDBUsers()
    {
        return R::getAll("select * from $this->dbTableName");
    }

    // ---получить пользователя внутренней авторизации по логину---
    public function getDBUser(string $login)
    {
        $sql = "select * from $this->dbTableName where login = :login";
        $args = ['login' => $login];
        $queryResult = R::getAll($sql, $args);

        return empty($queryResult) ? false : $queryResult[0];
    }

    /** проверить существование пользователя */
    public function exists($login, $authType): bool
    {
        if ($authType === 'db') {
            $tableName = $this->dbTableName;
            $condition = 'login = :login';
            $args = ['login' => $login];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $tableName = $this->authServiceUsers;
            $condition = 'login = :login and auth_service_id = :auth_service_id';
            $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        } else {
            throw new Exception('Неверный тип авторизации');
        }

        return R::count($tableName, $condition, $args) > 0;
    }

    // проверка авторизации
    public function is_correct_password($login, $password): bool
    {
        $sql = 'select password from db_users where login=:login';
        $args = ['login' => $login];
        $passHash = R::getCell($sql, $args);

        return password_verify($password, $passHash);
    }

    // добавить нового пользователя
    public function add($args, $authType = 'db'): int
    {
        if ($authType === 'db') {
            $user = R::xdispense('db_users');
            if (isset($args['is_admin'])) {
                $user->is_admin = 1;
            }
            $user->login = $args['email'];
            $user->password = password_hash($args['password'], PASSWORD_DEFAULT);
        } elseif ($authType === 'vk' || $authType === 'google') {
            $user = R::xdispense('auth_service_users');
            $user->token = $args['token'];
            $user->auth_service_id = $this->authServiceIds[$authType];
        } else {
            throw new Exception('Неверный тип авторизации');
        }

        return R::store($user);
    }

    // удалить пользователя
    public function remove(string $login)
    {
        $respArr = R::find($this->dbTableName, 'login = :login', ['login' => $login]);
        $user = array_values($respArr)[0];

        return R::trash($user);
    }

    // запись ВК-токена
    public function writeToken(string $login, string $token, string $authType): bool
    {
        if ($authType === 'db') {
            $dbTableName = $this->dbTableName;
            $condition = 'login = :login';
            $args = ['login' => $login];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $dbTableName = $this->authServiceUsers;
            $condition = 'login = :login and auth_service_id = :auth_service_id';
            $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        } else {
            throw new Exception('Неверный тип авторизации');
        }
        $respArr = R::find($dbTableName, $condition, $args);
        $user = array_values($respArr)[0];
        $user->token = $token;

        return R::store($user);
    }

    public function getToken(string $login, string $authType): string
    {
        $sql = 'select token from auth_service_users where login = :login and auth_service_id = :auth_service_id';
        $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        $queryResult = R::getAll($sql, $args);

        return empty($queryResult) ? false : $queryResult[0];
    }
}
