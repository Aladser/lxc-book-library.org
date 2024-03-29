<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Автор */
class Author extends Model
{
    private string $tableName = 'authors';

    // все авторы
    public function get_all()
    {
        $sql = "select name, surname from {$this->tableName} order by surname";
        $queryResult = R::getAll($sql);

        foreach ($queryResult as $dbAuthor) {
            $authors[] = [
                'name' => $dbAuthor['name'],
                'surname' => $dbAuthor['surname'],
            ];
        }

        return $authors;
    }

    // получить автора
    public function get($id)
    {
        return R::load($this->tableName, $id);
    }

    // получить id
    public function get_id(string $name, string $surname)
    {
        $condition = 'name = :name and surname = :surname';
        $args = ['name' => $name, 'surname' => $surname];
        $rows = R::find($this->tableName, $condition, $args);

        if (empty($rows)) {
            return false;
        }

        foreach ($rows as $key => $value) {
            return $key;
        }
    }

    // добавить
    public function add(string $name, string $surname): mixed
    {
        $author = R::dispense($this->tableName);
        $author->name = $name;
        $author->surname = $surname;

        return R::store($author);
    }

    // изменить
    public function update(string $new_name, string $new_surname, string $old_name, string $old_surname): mixed
    {
        $condition = 'where name=:old_name and surname=:old_surname';
        $args = [
            'old_name' => $old_name,
            'old_surname' => $old_surname,
        ];
        $author = Model::find($this->tableName, $condition, $args);

        $author->name = $new_name;
        $author->surname = $new_surname;

        return R::store($author) > 0;
    }

    // удалить
    public function remove(string $name, string $surname): mixed
    {
        $condition = 'where name=:name and surname=:surname';
        $args = [
            'name' => $name,
            'surname' => $surname,
        ];
        $author = Model::find($this->tableName, $condition, $args);

        return R::trash($author);
    }

    // проверить существование
    public function exists(string $name, string $surname): bool
    {
        $condition = 'name=:name and surname=:surname';
        $args = ['name' => $name, 'surname' => $surname];

        return R::count($this->tableName, $condition, $args) > 0;
    }
}
