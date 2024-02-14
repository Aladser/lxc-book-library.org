<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** таблица авторов */
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
        $args = ['name' => $name];

        return R::count($this->tableName, 'name = :name', $args) > 0;
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
        $queryResult = R::find($this->tableName, 'name = :name', ['name' => $name]);
        $genre = array_values($queryResult)[0];

        return R::trash($genre);
    }
}
