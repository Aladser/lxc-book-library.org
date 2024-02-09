<?php

namespace App\Models;

use App\Core\Model;

/** таблица авторов */
class Genre extends Model
{
    private string $tableName = 'genres';

    // список всех авторов
    public function get()
    {
        $sql = "select name from {$this->tableName} order by name";
        $sqlResult = $this->dbQuery->query($sql, false);
        $genres = [];
        foreach ($sqlResult as $genre) {
            $genres[] = $genre['name'];
        }

        return $genres;
    }

    // проверить существование
    public function exists(string $name, string $surname): bool
    {
    }

    // добавить
    public function add(string $name, string $surname): mixed
    {
    }

    // удалить
    public function remove(string $name, string $surname): mixed
    {
    }
}
