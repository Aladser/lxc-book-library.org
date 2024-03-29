<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Пользователь */
class User extends Model
{
    // id сервисов авторизации в БД
    private array $authServiceIds;
    private string $innerUserTableName = 'db_users';
    private string $authServiceUserTableName = 'auth_service_users';

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
        return R::getAll("select * from $this->innerUserTableName order by login");
    }

    // ---получить пользователя внутренней авторизации по логину---
    public function getDBUser(string $login)
    {
        $sql = "select * from $this->innerUserTableName where login = :login";
        $args = ['login' => $login];
        $queryResult = R::getAll($sql, $args);

        return empty($queryResult) ? false : $queryResult[0];
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
            $user->login = $args['login'];
        } else {
            throw new Exception('Неверный тип авторизации');
        }

        return R::store($user);
    }

    // удалить пользователя
    public function remove(string $login)
    {
        $condition = 'login = :login';
        $args = ['login' => $login];
        $user = Model::find($this->innerUserTableName, $condition, $args);

        return R::trash($user);
    }

    /** проверить существование пользователя */
    public function exists($login, $authType): bool
    {
        if ($authType === 'db') {
            $tableName = $this->innerUserTableName;
            $condition = 'login = :login';
            $args = ['login' => $login];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $tableName = $this->authServiceUserTableName;
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

    // запись токена
    public function writeToken(string $login, string $token, string $authType): bool
    {
        if ($authType === 'db') {
            $innerUserTableName = $this->innerUserTableName;
            $condition = 'login = :login';
            $args = ['login' => $login];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $innerUserTableName = $this->authServiceUserTableName;
            $condition = 'login = :login and auth_service_id = :auth_service_id';
            $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        } else {
            throw new Exception('Неверный тип авторизации');
        }
        $user = Model::find($innerUserTableName, $condition, $args);
        $user->token = $token;

        return R::store($user);
    }

    // получить токен
    public function getToken(string $login, string $authType): mixed
    {
        $sql = 'select token from auth_service_users where login = :login and auth_service_id = :auth_service_id';
        $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        $queryResult = R::getAll($sql, $args);

        return !empty($queryResult) ? $queryResult[0]['token'] : false;
    }
}
