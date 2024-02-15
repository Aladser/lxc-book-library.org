<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Жанр */
class Genre extends Model
{
    private string $tableName = 'genres';

    // все авторы
    public function get()
    {
        $queryResult = R::getAll("select name from {$this->tableName} order by name");
        $genres = [];
        foreach ($queryResult as $row) {
            $genres[] = $row['name'];
        }

        return $genres;
    }

    // проверить существование
    public function exists(string $name): bool
    {
        $condition = 'name = :name';
        $args = ['name' => $name];

        return R::count($this->tableName, $condition, $args) > 0;
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
}
