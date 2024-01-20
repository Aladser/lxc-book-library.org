<?php

namespace App\Models;

use App\Core\Model;

/** таблица пользователей */
class User extends Model
{
    /** проверить существование пользователя */
    public function exists($login): bool
    {
        $sql = 'select count(*) as count from db_users where login = :login';
        $args = ['login' => $login];
        $isExisted = $this->dbQuery->queryPrepared($sql, $args)['count'] == 1;

        return $isExisted;
    }

    // проверка авторизации
    public function is_correct_password($login, $password): bool
    {
        $sql = 'select password from users where login=:login';
        $args = ['login' => $login];
        $passHash = $this->dbQuery->queryPrepared($sql, $args)['password'];

        return password_verify($password, $passHash);
    }

    // добавить нового пользователя
    public function add($login, $password): int
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'insert into db_users(login, password) values(:login, :password)';
        $args = ['login' => $login, 'password' => $passwordHash];
        $db_user_id = $this->dbQuery->insert($sql, $args);

        $sql = 'insert into users(db_user_id) values(:id)';
        $args = ['id' => $db_user_id];
        $user_id = $this->dbQuery->insert($sql, $args);

        return $user_id;
    }

    // получить ID пользователя
    public function getId(string $login)
    {
        $sql = 'select id from users where login = :login';
        $args = ['login' => $login];
        $id = $this->dbQuery->queryPrepared($sql, $args)['id'];

        return $id;
    }
}
