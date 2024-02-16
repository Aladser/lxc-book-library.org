<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Жанр */
class Genre extends Model
{
    private string $tableName = 'genres';

    // все жанры
    public function get_all()
    {
        $queryResult = R::getAll("select name from {$this->tableName} order by name");
        $genres = [];
        foreach ($queryResult as $row) {
            $genres[] = $row['name'];
        }

        return $genres;
    }

    // получить id
    public function get_id(string $name)
    {
        $condition = 'name = :name';
        $args = ['name' => $name];
        $rows = R::find($this->tableName, $condition, $args);

        if (empty($rows)) {
            return false;
        }

        foreach ($rows as $key => $value) {
            return $key;
        }
    }

    // добавить
    public function add(string $name): int
    {
        $genre = R::dispense($this->tableName);
        $genre->name = $name;

        return R::store($genre);
    }

    // удалить
    public function remove(string $name)
    {
        $condition = 'name = :name';
        $args = ['name' => $name];
        $genre = Model::find($this->tableName, $condition, $args);

        return R::trash($genre);
    }

    // проверить существование
    public function exists(string $name): bool
    {
        $condition = 'name = :name';
        $args = ['name' => $name];

        return R::count($this->tableName, $condition, $args) > 0;
    }
}
