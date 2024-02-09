<?php

namespace App\Models;

use App\Core\Model;

/** таблица авторов */
class Author extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // список всех авторов
    public function get()
    {
        $sql = 'select name, surname from authors order by surname';
        $dbAuthors = $this->dbQuery->query($sql, false);
        foreach ($dbAuthors as $dbAuthor) {
            $authors[] = [
                'name' => $dbAuthor['name'],
                'surname' => $dbAuthor['surname'],
            ];
        }

        return $authors;
    }

    // проверить существование
    public function exists(string $name, string $surname): bool
    {
        $sql = 'select count(*) as count from authors 
        where name=:name and surname=:surname';
        $args = ['name' => $name, 'surname' => $surname];

        return $this->dbQuery->queryPrepared($sql, $args)['count'] > 0;
    }

    // добавить
    public function add(string $name, string $surname): mixed
    {
        $sql = 'insert into authors(name, surname) values(:name, :surname)';
        $args = ['name' => $name, 'surname' => $surname];

        return $this->dbQuery->insert($sql, $args);
    }

    // изменить
    public function update(string $new_name, string $new_surname, string $old_name, string $old_surname): mixed
    {
        $sql = 'update authors set name=:new_name, surname=:new_surname 
        where name=:old_name and surname=:old_surname';
        $args = [
            'new_name' => $new_name,
            'new_surname' => $new_surname,
            'old_name' => $old_name,
            'old_surname' => $old_surname,
        ];

        return $this->dbQuery->update($sql, $args);
    }

    // удалить
    public function remove(string $name, string $surname): mixed
    {
        $sql = 'delete from authors where name=:name and surname=:surname';
        $args = ['name' => $name, 'surname' => $surname];

        return $this->dbQuery->delete($sql, $args);
    }
}
