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
        $response = R::getAll("select name from {$this->tableName} order by name");
        $genres = [];
        foreach ($response as $row) {
            $genres[] = $row['name'];
        }

        return $genres;
    }

    // проверить существование
    public function exists(string $name): bool
    {
        $args = ['name' => $name];

        return R::count($this->tableName, 'name=:name', $args) > 0;
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
        $respArr = R::find($this->tableName, "name = $name");
        $genre = array_values($respArr)[0];

        return R::trash($genre);
    }
}
