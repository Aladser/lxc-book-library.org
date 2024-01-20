<?php

namespace App\Models;

use App\Core\Model;

/** таблица пользователей */
class User extends Model
{
    /** проверить существование пользователя */
    public function exists($login, $type): bool
    {
        if ($type != 'db' && $type != 'vk') {
            throw new Exception('Неверный тип авторизации');
        }

        $sql = "select count(*) as count from {$type}_users where login = :login";
        $args = ['login' => $login];
        $isExisted = $this->dbQuery->queryPrepared($sql, $args)['count'] == 1;

        return $isExisted;
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
    public function add($args, $type = 'db'): int
    {
        if ($type === 'db') {
            $args['password'] = password_hash($args['password'], PASSWORD_DEFAULT);
            $sql = 'insert into db_users(login, password) values(:login, :password)';
        } elseif ($type === 'vk') {
            $sql = 'insert into vk_users(login, token) values(:login, :token)';
        } else {
            throw new Exception('Неверный тип регистрации');
        }

        $user_id = $this->dbQuery->insert($sql, $args);
        $sql = "insert into users({$type}_user_id) values(:id)";
        $args = ['id' => $user_id];
        $general_user_id = $this->dbQuery->insert($sql, $args);

        return $general_user_id;
    }

    // получить ID пользователя
    public function getId(string $login)
    {
        $sql = 'select id from users where login = :login';
        $args = ['login' => $login];

        return $this->dbQuery->queryPrepared($sql, $args)['id'];
    }

    // запись ВК-токена
    public function writeVKToken(int $login, string $token): bool
    {
        $sql = 'update vk_users set token = :token where login = :login';
        $args = ['login' => $login, 'token' => $token];

        return $this->dbQuery->update($sql, $args);
    }
}
