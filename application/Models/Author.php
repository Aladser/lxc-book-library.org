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

    // список авторов
    public function get()
    {
        $sql = 'select name, surname from authors order by surname';
        $dbAuthors = $this->dbQuery->query($sql, false);
        foreach ($dbAuthors as $dbAuthor) {
            $authors[] = ['name' => $dbAuthor['name'], 'surname' => $dbAuthor['surname']];
        }

        return $authors;
    }

    /** проверить существование автора*/
    public function exists(string $name, string $surname): bool
    {
        $sql = 'select count(*) as count from authors where name=:name and surname=:surname';
        $args = ['name' => $name, 'surname' => $surname];

        return $this->dbQuery->queryPrepared($sql, $args)['count'] > 0;
    }

    // добавить нового автора
    public function add(): int
    {
    }
}
