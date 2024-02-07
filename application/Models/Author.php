<?php

namespace App\Models;

use App\Core\Model;

/** таблица пользователей */
class Author extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // список авторов
    public function get()
    {
        $sql = 'select name, surname from authors';
        $dbAuthors = $this->dbQuery->query($sql, false);
        foreach ($dbAuthors as $dbAuthor) {
            $authors[] = ['name' => $dbAuthor['name'], 'surname' => $dbAuthor['surname']];
        }

        return $authors;
    }

    /** проверить существование пользователя */
    public function exists(): bool
    {
    }

    // добавить нового пользователя
    public function add(): int
    {
    }
}
