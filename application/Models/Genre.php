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
    public function exists(string $name): bool
    {
        $sql = "select count(*) as count from {$this->tableName} where name=:name";
        $args = ['name' => $name];

        return $this->dbQuery->queryPrepared($sql, $args)['count'] > 0;
    }

    // добавить
    public function add(string $name): mixed
    {
        $sql = "insert into {$this->tableName}(name) values(:name)";
        $args = ['name' => $name];

        return $this->dbQuery->insert($sql, $args);
    }

    // удалить
    public function remove(string $name): mixed
    {
        $sql = "delete from {$this->tableName} where name=:name";
        $args = ['name' => $name];

        return $this->dbQuery->delete($sql, $args);
    }
}
