<?php

namespace App\Models;

use App\Core\Model;

/** таблица пользователей */
class User extends Model
{
    // id сервисов авторизации в БД
    private array $authServiceIds;

    public function __construct()
    {
        parent::__construct();
        $this->authServiceIds = ['vk' => 1, 'google' => 2];
    }

    /** проверить существование пользователя */
    public function exists($login, $authType): bool
    {
        if ($authType === 'db') {
            $sql = 'select count(*) as count from db_users where login = :login';
            $args = ['login' => $login];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $sql = 'select count(*) as count from auth_service_users where login = :login and auth_service_id = :auth_service_id';
            $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        } else {
            throw new Exception('Неверный тип авторизации');
        }

        return $this->dbQuery->queryPrepared($sql, $args)['count'] == 1;
    }

    // проверка авторизации
    public function is_correct_password($login, $password): bool
    {
        $sql = 'select password from db_users where login=:login';
        $args = ['login' => $login];
        $passHash = $this->dbQuery->queryPrepared($sql, $args)['password'];

        return password_verify($password, $passHash);
    }

    // добавить нового пользователя
    public function add($args, $authType = 'db'): int
    {
        if ($authType === 'db') {
            $sql = 'insert into db_users(login, password) values(:email, :password)';
            $args['password'] = password_hash($args['password'], PASSWORD_DEFAULT);
        } elseif ($authType === 'vk' || $authType === 'google') {
            $sql = 'insert into auth_service_users(login, token, auth_service_id) values(:login, :token, :auth_service_id)';
            $args['auth_service_id'] = $this->authServiceIds[$authType];
        } else {
            throw new Exception('Неверный тип авторизации');
        }
        $user_id = $this->dbQuery->insert($sql, $args);

        return $user_id;
    }

    public function getDBUser(string $login)
    {
        $sql = 'select * from db_users where login = :login';
        $args = ['login' => $login];

        return $this->dbQuery->queryPrepared($sql, $args);
    }

    // запись ВК-токена
    public function writeToken(string $login, string $token, string $authType): bool
    {
        if ($authType === 'db') {
            $sql = 'update db_users set token = :token where login = :login';
            $args = ['login' => $login, 'token' => $token];
        } elseif ($authType === 'vk' || $authType === 'google') {
            $sql = 'update auth_service_users set token = :token where login = :login and auth_service_id = :auth_service_id';
            $args = ['login' => $login, 'token' => $token, 'auth_service_id' => $this->authServiceIds[$authType]];
        } else {
            throw new Exception('Неверный тип авторизации');
        }

        return $this->dbQuery->update($sql, $args);
    }

    public function getToken(string $login, string $authType): string
    {
        $sql = 'select token from auth_service_users where login = :login and auth_service_id = :auth_service_id';
        $args = ['login' => $login, 'auth_service_id' => $this->authServiceIds[$authType]];
        $token = $this->dbQuery->queryPrepared($sql, $args)['token'];

        return $token;
    }
}
