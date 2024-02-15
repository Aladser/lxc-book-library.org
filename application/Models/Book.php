<?php

namespace App\Models;

use App\Core\Model;
use RedBeanPHP\R;

/** Книга */
class Book extends Model
{
    private string $tableName = 'books';

    /** все книги.
     *
     * @param bool $isFullData полные данные?
     */
    public function get_all($isFullData = true): array
    {
        $sql = 'select 
            books.id as id,
            authors.name as author_name, 
            authors.surname as author_surname, 
            books.name as name, 
            genres.name as genre';
        if ($isFullData) {
            $sql .= ', 
                books.year as year, 
                books.description as description, 
                books.picture as picture   
            ';
        }
        $sql .= '
            from books 
            join authors on author_id = authors.id
            join genres on genre_id = genres.id
            order by id
        ';

        $queryResult = R::getAll($sql);
        $books = [];
        foreach ($queryResult as $row) {
            $books[] = [
                'id' => (int) $row['id'],
                'author_name' => $row['author_name'].' '.$row['author_surname'],
                'name' => $row['name'],
                'genre' => $row['genre'],
            ];
            if ($isFullData) {
                $books['year'] = $row['year'];
                $books['description'] = $row['description'];
                $books['picture'] = $row['picture'];
            }
        }

        return $books;
    }

    public function get(int $id)
    {
        $book = R::load('books', $id);
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
