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

        return $this->dbQuery->update($sql, $args);
    }

    // добавить нового автора
    public function update(string $new_name, string $new_surname, string $old_name, string $old_surname): int
    {
        $sql = 'update authors set name=:new_name, surname=:new_surname where name=:old_name and surname=:old_surname';
        $args = ['new_name' => $new_name, 'new_surname' => $new_surname, 'old_name' => $old_name, 'old_surname' => $old_surname];
    }
}
